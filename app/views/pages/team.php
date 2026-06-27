

<style>
.team-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
}

.team-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.team-hero p {
    font-size: 1.3rem;
    opacity: 0.9;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 40px;
    padding: 80px 0;
}

.team-member-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.team-member-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.team-member-image {
    width: 100%;
    height: 250px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 80px;
    color: white;
}

.team-member-content {
    padding: 30px;
}

.team-member-name {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.team-member-title {
    font-size: 1rem;
    color: #667eea;
    font-weight: 600;
    margin-bottom: 10px;
}

.team-member-department {
    font-size: 0.9rem;
    color: #999;
    margin-bottom: 15px;
}

.team-member-bio {
    color: #666;
    line-height: 1.6;
    font-size: 0.95rem;
    margin-bottom: 20px;
    min-height: 80px;
}

.team-member-contact {
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
}

.team-member-contact a {
    display: inline-block;
    width: 35px;
    height: 35px;
    background: #f0f0f0;
    border-radius: 50%;
    line-height: 35px;
    text-decoration: none;
    color: #667eea;
    transition: all 0.3s ease;
}

.team-member-contact a:hover {
    background: #667eea;
    color: white;
}
</style>

<div class="team-hero">
    <div class="container">
        <h1>Meet Our Team</h1>
        <p>Talented professionals dedicated to delivering excellence</p>
    </div>
</div>

<div class="container">
    <div class="team-grid">
        <?php if (!empty($team_members)): ?>
            <?php foreach ($team_members as $member): ?>
                <div class="team-member-card">
                    <div class="team-member-image">
                        <?php 
                            $initials = strtoupper(substr($member['first_name'] ?? $member['name'], 0, 1) . substr($member['last_name'] ?? '', 0, 1));
                            echo $initials;
                        ?>
                    </div>
                    <div class="team-member-content">
                        <div class="team-member-name"><?= htmlspecialchars($member['name']) ?></div>
                        <div class="team-member-title"><?= htmlspecialchars($member['title']) ?></div>
                        <?php if (!empty($member['department'])): ?>
                            <div class="team-member-department"><?= htmlspecialchars($member['department']) ?></div>
                        <?php endif; ?>
                        <p class="team-member-bio"><?= htmlspecialchars($member['bio']) ?></p>
                        <div class="team-member-contact">
                            <?php if (!empty($member['email'])): ?>
                                <a href="mailto:<?= htmlspecialchars($member['email']) ?>" title="Email" data-toggle="tooltip">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($member['linkedin_url'])): ?>
                                <a href="<?= htmlspecialchars($member['linkedin_url']) ?>" target="_blank" title="LinkedIn" data-toggle="tooltip">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($member['twitter_url'])): ?>
                                <a href="<?= htmlspecialchars($member['twitter_url']) ?>" target="_blank" title="Twitter" data-toggle="tooltip">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center text-muted">Team members information coming soon.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
