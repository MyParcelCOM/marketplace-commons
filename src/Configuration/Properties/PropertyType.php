<?php

namespace MyParcelCom\Integration\Configuration\Properties;

enum PropertyType: string
{
    case STRING = 'string';
    case NUMBER = 'number';
    case BOOLEAN = 'boolean';
}
