<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'email',
        'password',
        'first_name',
        'last_name',
        'user_type',
        'avatar',
        'bio',
        'is_active',
        'email_verified_at',
        'verification_token',
        'reset_token',
        'reset_token_expires'
    ];

    // Hide sensitive fields from API responses
    protected $hidden = [
        'password',
        'verification_token',
        'reset_token',
        'reset_token_expires'
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
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Remove sensitive fields from user data before sending to client
     * Call this manually in controllers after authentication/processing
     */
    public function sanitizeUser(array &$user): void
    {
        foreach ($this->hidden as $field) {
            unset($user[$field]);
        }
    }

    /**
     * Sanitize multiple users
     */
    public function sanitizeUsers(array &$users): void
    {
        foreach ($users as &$user) {
            $this->sanitizeUser($user);
        }
    }
}
