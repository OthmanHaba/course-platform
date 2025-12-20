<?php

namespace App\Models;

use CodeIgniter\Model;

class AccessTokenModel extends Model
{
    protected $table            = 'personal_access_tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'token',
        'name',
        'abilities',
        'last_used_at',
        'expires_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Generate a new access token for a user
     *
     * @param int $userId
     * @param string|null $name
     * @param array $abilities
     * @param int|null $expiresInDays
     * @return array|null
     */
    public function createToken(int $userId, ?string $name = null, array $abilities = ['*'], ?int $expiresInDays = null): ?array
    {
        // Generate a random token (64 characters)
        $plainTextToken = bin2hex(random_bytes(32));

        // Hash the token for storage (similar to password hashing)
        $hashedToken = hash('sha256', $plainTextToken);

        // Calculate expiration date if specified
        $expiresAt = null;
        if ($expiresInDays !== null) {
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expiresInDays} days"));
        }

        // Store the hashed token in database
        $tokenData = [
            'user_id' => $userId,
            'token' => $hashedToken,
            'name' => $name,
            'abilities' => json_encode($abilities),
            'expires_at' => $expiresAt,
        ];

        $tokenId = $this->insert($tokenData);

        if (!$tokenId) {
            return null;
        }

        // Return the plain text token (only time it's available)
        return [
            'id' => $tokenId,
            'plain_text_token' => $plainTextToken,
            'hashed_token' => $hashedToken,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Verify a token and return the associated user
     *
     * @param string $plainTextToken
     * @return array|null User data if token is valid, null otherwise
     */
    public function verifyToken(string $plainTextToken): ?array
    {
        // Hash the provided token
        $hashedToken = hash('sha256', $plainTextToken);

        // Find the token in database
        $token = $this->where('token', $hashedToken)->first();

        if (!$token) {
            return null;
        }

        // Check if token is expired
        if ($token['expires_at'] && strtotime($token['expires_at']) < time()) {
            return null;
        }

        // Update last_used_at timestamp
        $this->update($token['id'], ['last_used_at' => date('Y-m-d H:i:s')]);

        // Get the user associated with this token
        $userModel = new UserModel();
        $user = $userModel->find($token['user_id']);

        if (!$user) {
            return null;
        }

        // Attach token info to user
        $user['current_token'] = $token;

        return $user;
    }

    /**
     * Revoke (delete) a specific token
     *
     * @param string $plainTextToken
     * @return bool
     */
    public function revokeToken(string $plainTextToken): bool
    {
        $hashedToken = hash('sha256', $plainTextToken);
        return $this->where('token', $hashedToken)->delete();
    }

    /**
     * Revoke all tokens for a user
     *
     * @param int $userId
     * @return bool
     */
    public function revokeAllUserTokens(int $userId): bool
    {
        return $this->where('user_id', $userId)->delete();
    }

    /**
     * Delete expired tokens (for cleanup)
     *
     * @return bool
     */
    public function deleteExpiredTokens(): bool
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))
                    ->where('expires_at IS NOT NULL')
                    ->delete();
    }
}
