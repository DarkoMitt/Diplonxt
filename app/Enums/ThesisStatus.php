<?php

namespace App\Enums;

enum ThesisStatus: string
{
    case EligibilityCheck = 'eligibility_check';
    case TopicSubmitted = 'topic_submitted';
    case TopicApproved = 'topic_approved';
    case RevisionRequired = 'revision_required';
    case Research = 'research';
    case Development = 'development';
    case Testing = 'testing';
    case PeerReview = 'peer_review';
    case DemoReview = 'demo_review';
    case DefenseApproved = 'defense_approved';
    case DefenseScheduled = 'defense_scheduled';
    case Pass = 'pass';
    case Fail = 'fail';
    case Completed = 'completed';
    case Archived = 'archived';

    public function label(): string
    {
        return str($this->value)->replace('_', ' ')->title()->toString();
    }

    public function progress(): int
    {
        return match ($this) {
            self::EligibilityCheck => 5,
            self::TopicSubmitted => 10,
            self::TopicApproved => 18,
            self::RevisionRequired => 25,
            self::Research => 32,
            self::Development => 48,
            self::Testing => 62,
            self::PeerReview => 72,
            self::DemoReview => 80,
            self::DefenseApproved => 88,
            self::DefenseScheduled => 92,
            self::Pass, self::Fail => 97,
            self::Completed, self::Archived => 100,
        };
    }
}
