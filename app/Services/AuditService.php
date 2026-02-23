<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    /**
     * Record an audit log entry.
     *
     * @param string $actorType 'member' or 'admin' or Model class name
     * @param int|string $actorId
     * @param string $action 'create', 'update', 'delete', 'login', etc.
     * @param string $entityType Model class name or entity name
     * @param int|string $entityId
     * @param array|null $changes
     * @return AuditLog
     */
    public function record($actorType, $actorId, string $action, string $entityType, $entityId, ?array $changes = null)
    {
        return AuditLog::create([
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'changes_json' => $changes,
        ]);
    }

    /**
     * Shorter alias for record, also used in some controllers.
     */
    public function log(string $action, string $entityType, $entityId, ?array $changes = null)
    {
        try {
            // Auto-detect actor from auth session
            $actorType = 'system';
            $actorId = 0;

            if (auth('sanctum')->check()) {
                $user = auth('sanctum')->user();
                if ($user) {
                    $actorId = $user->id;
                    $actorType = ($user instanceof \App\Models\AdminUser) ? 'admin' : 'member';
                }
            }

            return $this->record($actorType, $actorId, $action, $entityType, $entityId, $changes);
        } catch (\Exception $e) {
            // Log error to laravel log but don't break the application
            \Illuminate\Support\Facades\Log::error('Audit Log Error: ' . $e->getMessage());
            return null;
        }
    }

}
