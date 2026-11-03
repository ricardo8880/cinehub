<?php

return [
    // English shared by en-US, en-GB, en-AU and en-CA. Regional slang is assessed in context.
    // Patterns are deliberately focused on conduct, not isolated swear words.
    'blocked' => [
        '\\b(?:i(?:\\s+am|\\x27m|\\s+will|\\x27ll)\\s+(?:going\\s+to\\s+|gonna\\s+)?|im\\s+gonna\\s+)(?:kill|murder|hurt|stab|shoot|rape)\\s+(?:you|u|ya|your\\s+family)\\b',
        '\\b(?:i\\s+know\\s+where\\s+you\\s+live|you\\s+deserve\\s+to\\s+die|kill\\s+yourself|kys)\\b',
        '\\b(?:go\\s+(?:and\\s+)?(?:fuck|f\\*+ck)\\s+yourself|fuck\\s+off\\s+you\\s+(?:idiot|moron)|shut\\s+up\\s+you\\s+(?:idiot|moron))\\b',
        '\\b(?:fuck\\s+you|you\\s+(?:should|must)\\s+die|i\\s+(?:will|am\\s+going\\s+to)\\s+beat\\s+you\\s+up)\\b',
        '\\b(?:you\\s+(?:deserve|need)\\s+(?:a\\s+)?(?:beating|ass\\s+kicking)|i\\s+will\\s+(?:track|hunt)\\s+you\\s+down)\\b',
        '\\b(?:i\\s+(?:will|am\\s+going\\s+to)\\s+(?:share|post|leak)\\s+your\\s+(?:address|private\\s+(?:photos|pictures|data)))\\b',
        '\\b(?:you\\s+(?:are|\\x27re)\\s+(?:subhuman|worthless\\s+trash)|all\\s+(?:women|men)\\s+(?:deserve|should)\\s+(?:die|be\\s+hurt))\\b',
        '\\b(?:i\\s+want\\s+to\\s+(?:rape|assault)\\s+you|i\\s+will\\s+force\\s+you\\s+to\\s+(?:have\\s+sex|strip))\\b',
        '\\b(?:you(?:\\s+are|\\x27re|r)?\\s+(?:a|an)?\\s*(?:(?:fucking|stupid|dumb|worthless|pathetic)\\s+){0,2}(?:idiot|moron|retard|loser))\\b',
        '\\b(?:send\\s+(?:me|us)\\s+(?:nudes|naked\\s+(?:pics|photos))|show\\s+me\\s+your\\s+(?:genitals|boobs|dick))\\b',
        '\\b(?:child\\s+porn|cp\\s+(?:links?|videos?|pics?)|sex\\s+with\\s+(?:a\\s+)?(?:child|minor))\\b',
        // Severe racial slurs / directed dehumanization (limited list, administratively expandable).
        '\\b(?:nigg(?:er|a)s?|kike|spic|chink|paki)\\b',
    ],
    'review' => [
        '\\b(?:your\\s+(?:opinion|review|comment)\\s+is\\s+(?:shit|trash|garbage|crap)|what\\s+a\\s+(?:stupid|dumb)\\s+(?:take|opinion))\\b',
        '\\b(?:shut\\s+up|stfu|gtfo|piss\\s+off|screw\\s+you|bugger\\s+off)\\b',
        '\\b(?:porn|pornhub|onlyfans|nudes|xxx|explicit\\s+sex|sexual\\s+acts?)\\b',
        '\\b(?:(?:https?:\\/\\/|www\\.)\\S+|(?:telegram|whatsapp|discord)\\s*(?:me|at|link))\\b',
        '\\b(?:scam|free\\s+money|buy\\s+followers|click\\s+here|subscribe\\s+to\\s+my\\s+channel)\\b',
        '\\b(?:send\\s+crypto|guaranteed\\s+profit|investment\\s+opportunity|dm\\s+me\\s+for\\s+(?:details|money)|cashapp|bitcoin\\s+giveaway)\\b',
        '\\b(?:nobody\\s+cares\\s+about\\s+your\\s+opinion|you\\s+(?:are|sound)\\s+(?:so\\s+)?(?:clueless|ignorant|dumb))\\b',
        '\\b(?:explicit\\s+nudes|sex\\s+chat|adult\\s+content|cam\\s+show|escort\\s+service|hookup\\s+tonight)\\b',
        // US/UK/AU/CA informal insults; positive exclamations remain allowed.
        '\\b(?:wanker|tosser|dickhead|fuckwit|bellend|arsehole|asshole|dropkick|drongo|douchebag)\\b',
    ],
];
