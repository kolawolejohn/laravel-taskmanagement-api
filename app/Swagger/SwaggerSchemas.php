<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     required={"id", "title", "status"},
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         format="uuid",
 *         example="550e8400-e29b-41d4-a716-446655440000"
 *     ),
 *     @OA\Property(property="title", type="string", example="Finish the project"),
 *     @OA\Property(property="description", type="string", example="Complete all pending tasks"),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="scheduled_for", type="string", format="date-time", nullable=true, example="2025-05-22T14:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-21T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-22T12:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Pagination",
 *     type="object",
 *     properties={
 *         @OA\Property(property="total", type="integer", example=50),
 *         @OA\Property(property="per_page", type="integer", example=10),
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=5),
 *         @OA\Property(property="from", type="integer", nullable=true, example=1),
 *         @OA\Property(property="to", type="integer", nullable=true, example=10),
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="PaginatedTasksResponse",
 *     type="object",
 *     properties={
 *         @OA\Property(
 *             property="items",
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Task")
 *         ),
 *         @OA\Property(
 *             property="pagination",
 *             ref="#/components/schemas/Pagination"
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="TaskStoreRequest",
 *     type="object",
 *     required={"title", "status"},
 *     @OA\Property(property="title", type="string", example="New Task"),
 *     @OA\Property(property="description", type="string", example="Task details"),
 *     @OA\Property(property="status", type="string", example="pending")
 * )
 *
 * @OA\Schema(
 *     schema="TaskUpdateRequest",
 *     type="object",
 *     required={"title", "status"},
 *     @OA\Property(property="title", type="string", example="Updated Task Title"),
 *     @OA\Property(property="description", type="string", example="Updated task description"),
 *     @OA\Property(property="status", type="string", example="completed")
 * )
 */
class SwaggerSchemas
{
    // This class is just for OpenAPI annotation grouping
}
