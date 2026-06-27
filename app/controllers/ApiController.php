<?php
/**
 * API Controller - AI Chat and Search
 * GINTEC Solutions
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Faq;
use App\Models\ChatSession;

class ApiController extends Controller
{
    public function chat()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
        }

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['message']) || empty($data['message'])) {
            $this->json(['error' => 'Message is required'], 400);
        }

        $sessionId = $data['session_id'] ?? bin2hex(random_bytes(16));
        $message = \Core\Security::sanitize($data['message']);

        // Get or create chat session
        $session = (new ChatSession())->getOrCreate($sessionId);

        // Search FAQs for relevant answers
        $faqModel = new Faq();
        $relevantFaqs = $faqModel->search($message);

        // Build response from FAQs (simulated AI response)
        $response = $this->generateAiResponse($message, $relevantFaqs);

        // Save messages
        (new ChatSession())->saveMessage($sessionId, 'user', $message);
        (new ChatSession())->saveMessage($sessionId, 'assistant', $response);

        $this->json([
            'success' => true,
            'session_id' => $sessionId,
            'response' => $response,
            'suggested_faqs' => array_slice($relevantFaqs, 0, 3),
        ]);
    }

    public function searchFaqs()
    {
        if (!isset($_GET['q']) || empty($_GET['q'])) {
            $this->json(['results' => []]);
        }

        $query = \Core\Security::sanitize($_GET['q']);
        $results = (new Faq())->search($query);

        $this->json([
            'results' => $results,
            'count' => count($results),
        ]);
    }

    private function generateAiResponse($message, $faqs)
    {
        if (!empty($faqs)) {
            // Return the answer from the most relevant FAQ
            return $faqs[0]['answer'];
        }

        // Default response for unknown questions
        return "Thank you for your question! I didn't find a specific answer in our knowledge base. " .
               "Please contact our support team at " . config('company.email') . " for more assistance.";
    }
}
