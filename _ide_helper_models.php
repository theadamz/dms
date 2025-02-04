<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Basic{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property int $count
 * @property string|null $last_used
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Basic\ApprovalSetUser> $approval_set_users
 * @property-read int|null $approval_set_users_count
 * @property-read \App\Models\User|null $created_user
 * @property-read \App\Models\User|null $updated_user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet whereLastUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSet whereUpdatedBy($value)
 */
	class ApprovalSet extends \Eloquent {}
}

namespace App\Models\Basic{
/**
 * 
 *
 * @property string $id
 * @property string $approval_set_id
 * @property string $user_id
 * @property int $order
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Basic\ApprovalSet $approval_set
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser whereApprovalSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalSetUser whereUserId($value)
 */
	class ApprovalSetUser extends \Eloquent {}
}

namespace App\Models\Basic{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property bool $is_active
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedBy($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models\Basic{
/**
 * 
 *
 * @property string $id
 * @property string $category_id
 * @property string $name
 * @property bool $is_active
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Basic\Category $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategorySub whereUpdatedBy($value)
 */
	class CategorySub extends \Eloquent {}
}

namespace App\Models\Config{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property bool $is_active
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereUpdatedBy($value)
 */
	class Department extends \Eloquent {}
}

namespace App\Models\Config{
/**
 * 
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property string|null $def_path
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Config\TFactory|null $use_factory
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDefPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedBy($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models\Config{
/**
 * 
 *
 * @property string $id
 * @property string $role_id
 * @property string $code
 * @property string $permission read,edit,delete,validation,etc
 * @property bool $is_allowed
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereIsAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleAccess whereUpdatedBy($value)
 */
	class RoleAccess extends \Eloquent {}
}

namespace App\Models\Config{
/**
 * 
 *
 * @property string $id
 * @property string $ip
 * @property string|null $os
 * @property string|null $platform
 * @property string|null $browser
 * @property string|null $country
 * @property string|null $city
 * @property string $user_id
 * @property string $created_at
 * @property-read \App\Models\Config\TFactory|null $use_factory
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignInHistory whereUserId($value)
 */
	class SignInHistory extends \Eloquent {}
}

namespace App\Models\Config{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $code
 * @property string $permission something specific only for user
 * @property bool $is_allowed
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereIsAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAccess whereUserId($value)
 */
	class UserAccess extends \Eloquent {}
}

namespace App\Models\DMS{
/**
 * 
 *
 * @property string $id
 * @property string $doc_no
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string $category_sub_id
 * @property string $owner_id
 * @property string|null $notes
 * @property string $approval_workflow_type get from WorkflowType enum
 * @property bool $is_locked
 * @property string $review_workflow_type get from WorkflowType enum
 * @property bool $req_review
 * @property bool $is_reviewed
 * @property string $acknowledgement_workflow_type get from WorkflowType enum
 * @property bool $req_acknowledgement
 * @property bool $is_acknowledged
 * @property string $status
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Basic\CategorySub $category_sub
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereAcknowledgementWorkflowType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereApprovalWorkflowType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCategorySubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereDocNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereIsAcknowledged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereIsLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereIsReviewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereReqAcknowledgement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereReqReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereReviewWorkflowType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUpdatedBy($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models\DMS{
/**
 * 
 *
 * @property string $id
 * @property string $document_file_id
 * @property string $user_id
 * @property bool $is_acknowledged
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge whereDocumentFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge whereIsAcknowledged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAcknowledge whereUserId($value)
 */
	class DocumentAcknowledge extends \Eloquent {}
}

namespace App\Models\DMS{
/**
 * 
 *
 * @property string $id
 * @property string $document_file_id
 * @property string $user_id
 * @property int $order
 * @property bool $is_approved
 * @property string|null $remarks
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereDocumentFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentApproval whereUserId($value)
 */
	class DocumentApproval extends \Eloquent {}
}

namespace App\Models\DMS{
/**
 * 
 *
 * @property string $id
 * @property string $document_id
 * @property string|null $document_file_id when comment is on document level, this field is null
 * @property string $type get from DocumentCommentType enum
 * @property string $user_id
 * @property string $comment
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereDocumentFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentComment whereUserId($value)
 */
	class DocumentComment extends \Eloquent {}
}

namespace App\Models\DMS{
/**
 * 
 *
 * @property string $id
 * @property string $document_id
 * @property string $file_origin_name
 * @property string $file_name
 * @property int $file_size in bytes
 * @property string $file_ext
 * @property string $file_mime
 * @property string $created_by
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile whereFileExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile whereFileMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile whereFileOriginName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentFile whereId($value)
 */
	class DocumentFile extends \Eloquent {}
}

namespace App\Models\DMS{
/**
 * 
 *
 * @property string $id
 * @property string $document_id
 * @property string $user_id
 * @property string $action
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogs whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogs whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogs whereUserId($value)
 */
	class DocumentLogs extends \Eloquent {}
}

namespace App\Models\DMS{
/**
 * 
 *
 * @property string $id
 * @property string $document_file_id
 * @property string $user_id
 * @property bool $is_reviewed
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview whereDocumentFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview whereIsReviewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentReview whereUserId($value)
 */
	class DocumentReview extends \Eloquent {}
}

namespace App\Models\DMS{
/**
 * 
 *
 * @property string $id
 * @property int $year
 * @property int $next_no
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence whereNextNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSequence whereYear($value)
 */
	class DocumentSequence extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $role_id
 * @property string $department_id
 * @property string $timezone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $last_change_password_at
 * @property string|null $last_login_at
 * @property bool $is_active
 * @property string|null $picture
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Config\Department $department
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Config\Role $role
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastChangePasswordAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

