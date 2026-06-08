<?php

namespace App\Enums;

enum VersionStatus: string
{
    case Pending = 'pending';
    case Reviewed = 'reviewed';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case RevisionRequired = 'revision_required';

    public function label(): string
    {
        return str($this->value)->replace('_', ' ')->title()->toString();
    }
}
