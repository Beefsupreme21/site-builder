<?php

namespace App\Enums;

enum TemplateContext: string
{
    case Page = 'page';
    case Layout = 'layout';
    case System = 'system';
}
