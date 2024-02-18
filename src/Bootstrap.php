<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

use Aldavigdis\WpTestsStrapon\FetchWP;
use Aldavigdis\WpTestsStrapon\Config;
use Ansi;

class Bootstrap
{
    public const MIN_TERMINAL_WIDTH = 30;
    public const DEFAULT_TERMINAL_WIDTH = 80;
    public const MAX_TERMINAL_WIDTH = 120;

    public const INSPIRATIONS = [
        [
            'emoji' => '❤️',
            'text' => 'Take good care of yourself and those who love you.'
        ],
        [
            'emoji' => '🔪',
            'text' => 'The truth has the sharpest bite!'
        ],
        [
            'emoji' => '🏳️‍⚧️',
            'text' => 'Trans rights!'
        ],
        [
            'emoji' => '💼',
            'text' => 'Your productivity does not define your worth.'
        ],
        [
            'emoji' => '🥙',
            'text' => 'Have you remembered to eat today?'
        ],
        [
            'emoji' => '🌯',
            'text' => 'Happiness exsists in the centre of every burrito. ' .
                      '(And also falafel, if that\'s your jam)'
        ],
        [
            'emoji' => '🦄',
            'text' => 'Don\'t take calls during your day off.'
        ],
        [
            'emoji' => '🚴',
            'text' => 'Get that bike that you\'ve been wishing for. ' .
                      'Even if it\'s second hand.'
        ],
        [
            'emoji' => '🕓',
            'text' => 'Nine to five means you leave at five.'
        ],
        [
            'emoji' => '💊',
            'text' => 'Don\'t forget to take your meds!'
        ],
        [
            'emoji' => '👠',
            'text' => 'Chase your dreams in high heels!'
        ],
        [
            'emoji' => '✈️',
            'text' => 'Don\'t get fooled. Unlimited PTO is the biggest lie ' .
                      'in tech!'
        ],
        [
            'emoji' => '🌈',
            'text' => 'Support your local LGBT+ artists and local businesses.'
        ],
        [
            'emoji' => '✊',
            'text' => 'Join a union!'
        ],
        [
            'emoji' => '🧱',
            'text' => 'Stonewall was a riot!'
        ],
        [
            'emoji' => '🚀',
            'text' => 'Add more boosters!'
        ],
        [
            'emoji' => '👽',
            'text' => 'The truth is out there. (But it\'s probably not aliens.)'
        ],
        [
            'emoji' => '💸',
            'text' => 'WordPress developers get on average about 30% less ' .
                      'income than other software developers for the same ' .
                      'technical skills. (PHP, vanilla JavaScript, React, ' .
                      'CSS, HTML etc.)'
        ],
        [
            'emoji' => '🚨',
            'text' => 'Human Resources is cops.'
        ],
        [
            'emoji' => '🍽️',
            'text' => 'A good dishwasher is the best investment for anyone ' .
                      'working from home.'
        ],
        [
            'emoji' => '♿',
            'text' => 'Web accessibility is not just someone else\'s job. ' .
                      'Everyone in the industry should get familiar with ' .
                      'the WCAG guidelines.'
        ],
        [
            'emoji' => '🌈',
            'text' => 'Pride is for everyone.'
        ],
        [
            'emoji' => '🧠',
            'text' => 'If you lose your brain, you\'ll lose your livelihood, ' .
                      'so please take care of your mental health.'
        ],
        [
            'emoji' => '🚶',
            'text' => 'Passive exercise is also exercise. Having a good walk ' .
                      'is better than doning nothing as your gym membership ' .
                      'expires.'
        ],
        [
            'emoji' => '⛺',
            'text' => 'Widen your horizons. Don\'t get stuck with a single ' .
                      'CMS, framework or programming language. WordPress is ' .
                      'not going to stay relevant or exciting forever.'
        ],
        [
            'emoji' => '💄',
            'text' => 'Maybe she\'s born with it. ' .
                      'Maybe it\'s the test coverage.'
        ],
        [
            'emoji' => '🐝',
            'text' => 'Automated software testing is the bee\'s knees.'
        ],
        [
            'emoji' => '🤯',
            'text' => 'When your employer tells you to "embrace the chaos", ' .
                      'it\'s a cover for their awful management style and ' .
                      'company culture. Your lack of success in such a ' .
                      'setting is not your own fault.'
        ],
        [
            'emoji' => '❤️',
            'text' => 'Don\'t abandon your real family for your job.'
        ],
        [
            'emoji' => '🫠',
            'text' => 'Perhaps your infrastructure scales, but does your ' .
                      'company culture scale along with it?'
        ],
        [
            'emoji' => '✨',
            'text' => 'If all else fails, add more glitter.'
        ],
        [
            'emoji' => '🔥',
            'text' => 'Violence shouldn\'t be an occupational hazard.'
        ],
        [
            'emoji' => '☂️',
            'text' => 'Making sex work illegal doesn\'t make women\'s lives ' .
                      'any safer.'
        ]
    ];

    /**
     * Queer Emoji
     *
     * Those are used in the horizontal banners.
     *
     * Terminals have some issues with emoji based on ZWJ sequences, so we need
     * to avoid flags and such here.
     */
    public const QUEER_EMOJI = ['🦄', '🌈', '🍭', '🧁', '👑', '💎', '🧿',
                                '🩷', '🦩', '💖', '✨', '💗', '🎀', '🩰',
                                '👠', '💄', '💅', '💍', '🐻'];

    public static function init(string $wp_version)
    {
        self::displayLogotype();
        self::displaySeparator();

        self::displayLine(
            "Welcome to Alda's WP-Tests-Strapon Package!",
            '🌊'
        );
        self::displayLine(
            'Inspecting your WordPress test environment...',
            '👁️'
        );

        echo PHP_EOL;

        if (self::configExsists() === false) {
            self::displayLine(
                'A test config file for the WordPress test environment was ' .
                'not found.',
                '👀'
            );

            $config = new Config(wp_version: $wp_version);

            if ($config->testDatabaseConnection() === true) {
                self::displayLine(
                    'Connection to the database was successful.'
                );
            } else {
                self::displayLine(
                    'Unable to connect to the database. Check if the ' .
                    'database name, hostname, username or password are valid ' .
                    'and correct.',
                    '👎'
                );
                echo PHP_EOL;
            }

            if ($config->save() === true) {
                $path = Config::path();
                self::displayLine("A fresh config was saved to '$path'.");
                self::displayLine(
                    'The configuration parameters in the config file are ' .
                    'based on environment variables.',
                    '🖖'
                );
                self::displayLine(
                    "You can set those in your 'phpunit.xml', in your " .
                    'terminal etc.',
                    '⌨️'
                );
                self::displayLine(
                    'Read all about it in the WP-Tests-Strapon readme file.',
                    '📃'
                );
                echo PHP_EOL;
            }
        }

        if (FetchWP::isInstalled('develop-trunk', 'wordpress') === false) {
            self::displayLine(
                'A WordPress develop-trunk environment was not found.',
                '❓'
            );

            self::displayLine(
                'Downloading and installing the WordPress-develop-trunk ' .
                'environment...',
                '💻'
            );

            $trunk_file_path = FetchWP::downloadArchive(
                FetchWP::DEFAULT_WP_DEV_BASE_VERSION,
                FetchWP::WP_DEV_BASE_URL
            );

            if (is_string($trunk_file_path) === true) {
                self::displayLine('Done!');
            } else {
                self::displayLine(
                    'Sorry! Could not download this WordPress-develop-trunk.',
                    '👎'
                ) ;
                die;
            }

            self::displayLine('Extracting archive...', '🗜️');

            if (FetchWP::extractArchive($trunk_file_path)) {
                self::displayLine('Done!');
            } else {
                self::displayLine(
                    'Sorry! Could not extract the archive.',
                    '👎'
                );
                die;
            }

            echo PHP_EOL;
        }

        if (FetchWP::isInstalled($wp_version) === false) {
            self::displayLine(
                "A WordPress test environment for version '$wp_version' " .
                "was not found.",
                '❓'
            );
            self::displayLine(
                "Downloading and installing WordPress '$wp_version'...",
                '💻'
            );
            $archive_file_path = FetchWP::downloadArchive($wp_version);

            if (is_string($archive_file_path) === true) {
                self::displayLine('Done!');
            } else {
                self::displayLine(
                    'Sorry! Could not download this version of WordPress.',
                    '👎'
                ) ;
                die;
            }

            echo PHP_EOL;
            self::displayLine('Extracting archive...', '🗜️');

            if (FetchWP::extractArchive($archive_file_path)) {
                self::displayLine('Done!');
            } else {
                self::displayLine(
                    'Sorry! Could not extract the archive.',
                    '👎'
                );
                die;
            }

            echo PHP_EOL;

            self::displayLine(
                'Installation of test environment finished!',
                '🥂'
            );
            echo PHP_EOL;
        } else {
            self::displayInspiration();
            echo PHP_EOL;
        }

        self::displayLine(
            "Handing you over to the WordPress '$wp_version' test environment!",
            '➡️'
        );
        self::displayLine('Bye!', '👋');

        self::displaySeparator();
    }

    public static function terminalWidth(
        int $min = self::MIN_TERMINAL_WIDTH,
        int $max = self::MAX_TERMINAL_WIDTH
    ): int {
        if (getenv('TERM') === '' || getenv('TERM') === false) {
            return self::DEFAULT_TERMINAL_WIDTH;
        }

        $output = [];
        $result_code = 0;

        if (PHP_OS === 'WINNT') {
            @exec(
                command: 'powershell -command $Host.UI.RawUI.WindowSize.Width',
                output: $output,
                result_code: $result_code
            );
        } else {
            @exec(
                command: 'tput cols',
                output: $output,
                result_code: $result_code
            );
        }

        if (intval($result_code) !== 0) {
            return self::DEFAULT_TERMINAL_WIDTH;
        }

        $width = intval($output[0]);

        if ($width < $min) {
            return $min;
        }

        if ($width > $max) {
            return $max;
        }

        return $width;
    }

    public static function displaySeparator(): void
    {
        $halfwidth = intval((self::terminalWidth() / 2) - 4);

        $emoji_key = array_rand(self::QUEER_EMOJI);
        $emoji     = self::QUEER_EMOJI[$emoji_key];

        echo PHP_EOL .
             Ansi::tagsToColors('<magenta>' . str_repeat('═', $halfwidth)) .
             Ansi::tagsToColors('<magenta>[') .
             '  ' . $emoji . '  ' .
             Ansi::tagsToColors('<magenta>]') .
             Ansi::tagsToColors('<magenta>' . str_repeat('═', $halfwidth)) .
             PHP_EOL . PHP_EOL;
    }

    public static function displayLine(string $text, ?string $emoji = null): void
    {
        $terminal_width = self::terminalWidth() - 10;

        if (is_null($emoji) === true) {
            $emoji = "✅";
        }
        $wrapped_text = wordwrap($text, $terminal_width, PHP_EOL . "\t", true);
        echo "  $emoji\t$wrapped_text" . PHP_EOL;
    }

    public static function displayInspiration(?object $inspo = null): void
    {
        if (is_null($inspo) === true) {
            $inspo = self::pickInspiration();
        }
        self::displayLine($inspo->text, $inspo->emoji);
    }

    public static function displayLogotype(): void
    {
        $spaces = str_repeat(' ', intval((self::terminalWidth() / 2) - 14));

        if (strlen($spaces) < 0) {
            $spaces = ' ';
        }

        echo PHP_EOL;
        echo Ansi::tagsToColors("$spaces<blue>__        ______ <magenta>_____ ____  " . PHP_EOL);
        echo Ansi::tagsToColors("$spaces<blue>\ \      / /  _ \<magenta>_   _/ ___| " . PHP_EOL);
        echo Ansi::tagsToColors("$spaces<blue> \ \ /\ / /| |_) |<magenta>| | \___ \ " . PHP_EOL);
        echo Ansi::tagsToColors("$spaces<blue>  \ V  V / |  __/ <magenta>| |  ___) |" . PHP_EOL);
        echo Ansi::tagsToColors("$spaces<blue>   \_/\_/  |_|    <magenta>|_| |____/ " . PHP_EOL);
        echo PHP_EOL;
    }

    public static function pickInspiration(): object
    {
        $key = array_rand(self::INSPIRATIONS);
        return (object) self::INSPIRATIONS[$key];
    }

    public static function configExsists()
    {
        $path = Config::path();

        return is_file($path);
    }
}
