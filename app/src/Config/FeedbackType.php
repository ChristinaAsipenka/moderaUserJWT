<?php

namespace App\Config;

enum FeedbackType: string
{
    case How_I_like_it = 'How I like it';
    case Will_I_recommend = 'Will I recommend this post to my friends';
}
