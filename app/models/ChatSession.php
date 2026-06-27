<?php
/**
 * AI Chat Session Model
 * GINTEC Solutions
 */

namespace App\Models;

use Core\Model;

class ChatSession extends Model
{
    protected $table = 'chat_sessions';
    protected $fillable = ['session_id', 'user_id', 'conversation_data'];

    public function getOrCreate($sessionId)
    {
        $existing = $this->first('session_id', $sessionId);

        if ($existing) {
            return $existing;
        }

        $id = $this->create([
            'session_id' => $sessionId,
            'user_id' => auth_id(),
        ]);

        return $this->find($id);
    }

    public function saveMessage($sessionId, $role, $content)
    {
        $session = $this->first('session_id', $sessionId);

        if (!$session) {
            return false;
        }

        $messages = json_decode($session['conversation_data'] ?? '[]', true);
        $messages[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        return $this->update($session['id'], [
            'conversation_data' => json_encode($messages),
        ]);
    }
}
