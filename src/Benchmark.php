<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Bench;

use Hoa\Compiler\Llk\Lexer as Hoa;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use Railt\Io\File;
use Railt\Lexer\Builder;
use Railt\Lexer\Lexer;
use Railt\Lexer\LexerInterface;

/**
 * @Revs(1)
 * @BeforeMethods({"composer", "bootHoa", "bootRailt"})
 */
class Benchmark
{
    /**
     * @var Hoa
     */
    private $hoa;

    /**
     * @var array
     */
    private $hoaTokens = [];

    /**
     * @var LexerInterface
     */
    private $railt;

    /**
     * @return void
     */
    public function composer(): void
    {
        require __DIR__ . '/../vendor/autoload.php';
    }

    /**
     * @return void
     */
    public function bootRailt(): void
    {
        $builder = new Builder();

        $tokens = [
            'default' => [
                'skip'                    => '\n',
                'negative_class_'         => '\[\^',
                'class_'                  => '\[',
                '_class'                  => '\]',
                'range'                   => '\-',
                'internal_option'         => '\(\?[\-+]?[imsx]\)',
                'lookahead_'              => '\(\?=',
                'negative_lookahead_'     => '\(\?!',
                'lookbehind_'             => '\(\?<=',
                'negative_lookbehind_'    => '\(\?<!',
                'named_reference_'        => '\(\?\(<',
                'absolute_reference_'     => '\(\?\((?=\d)',
                'relative_reference_'     => '\(\?\((?=[\+\-])',
                'assertion_reference_'    => '\(\?\(',
                'comment_'                => '\(\?#',
                'named_capturing_'        => '\(\?<',
                'non_capturing_'          => '\(\?:',
                'non_capturing_reset_'    => '\(\?\|',
                'atomic_group_'           => '\(\?>',
                'capturing_'              => '\(',
                '_capturing'              => '\)',
                'zero_or_one_possessive'  => '\?\+',
                'zero_or_one_lazy'        => '\?\?',
                'zero_or_one'             => '\?',
                'zero_or_more_possessive' => '\*\+',
                'zero_or_more_lazy'       => '\*\?',
                'zero_or_more'            => '\*',
                'one_or_more_possessive'  => '\+\+',
                'one_or_more_lazy'        => '\+\?',
                'one_or_more'             => '\+',
                'exactly_n'               => '\{[0-9]+\}',
                'n_to_m_possessive'       => '\{[0-9]+,[0-9]+\}\+',
                'n_to_m_lazy'             => '\{[0-9]+,[0-9]+\}\?',
                'n_to_m'                  => '\{[0-9]+,[0-9]+\}',
                'n_or_more_possessive'    => '\{[0-9]+,\}\+',
                'n_or_more_lazy'          => '\{[0-9]+,\}\?',
                'n_or_more'               => '\{[0-9]+,\}',
                'alternation'             => '\|',
                'character'               => '\\\([aefnrt]|c[\x00-\x7f])',
                'dynamic_character'       => '\\\([0-7]{3}|x[0-9a-zA-Z]{2}|x{[0-9a-zA-Z]+})',
                'character_type'          => '\\\([CdDhHNRsSvVwWX]|[pP]{[^}]+})',
                'anchor'                  => '\\\([bBAZzG])|\^|\$',
                'match_point_reset'       => '\\\K',
                'literal'                 => '\\\.|.',
            ],
            'c'       => [
                'index' => '[\+\-]?\d+',
            ],
            'co'      => [
                '_comment' => '\)',
                'comment'  => '.*?(?=(?<!\\\)\))',
            ],
            'nc'      => [
                '_named_capturing' => '>',
                'capturing_name'   => '.+?(?=(?<!\\\)>)',
            ],
        ];

        foreach ($tokens as $ns => $tokens) {
            foreach ($tokens as $name => $pattern) {
                $builder->add($name, $pattern, $ns);
            }
        }

        $this->railt = new Lexer($builder->getPatterns(), ['skip'], [
            'default' => [
                'named_reference_'    => 'nc',
                'absolute_reference_' => 'c',
                'relative_reference_' => 'c',
                'comment_'            => 'co',
                'named_capturing_'    => 'nc',
            ],
            'c'       => [
                'index' => 'default'
            ],
            'co'      => [
                '_comment' => 'default'
            ],
            'nc'      => [
                '_named_capturing' => 'default'
            ]
        ]);
    }

    /**
     * @return void
     */
    public function bootHoa(): void
    {
        $this->hoa = new Hoa();
        $this->hoaTokens = [
            'default' => [
                'skip'                    => '\n',
                'negative_class_'         => '\[\^',
                'class_'                  => '\[',
                '_class'                  => '\]',
                'range'                   => '\-',
                'internal_option'         => '\(\?[\-+]?[imsx]\)',
                'lookahead_'              => '\(\?=',
                'negative_lookahead_'     => '\(\?!',
                'lookbehind_'             => '\(\?<=',
                'negative_lookbehind_'    => '\(\?<!',
                'named_reference_:nc'     => '\(\?\(<',
                'absolute_reference_:c'   => '\(\?\((?=\d)',
                'relative_reference_:c'   => '\(\?\((?=[\+\-])',
                'assertion_reference_'    => '\(\?\(',
                'comment_:co'             => '\(\?#',
                'named_capturing_:nc'     => '\(\?<',
                'non_capturing_'          => '\(\?:',
                'non_capturing_reset_'    => '\(\?\|',
                'atomic_group_'           => '\(\?>',
                'capturing_'              => '\(',
                '_capturing'              => '\)',
                'zero_or_one_possessive'  => '\?\+',
                'zero_or_one_lazy'        => '\?\?',
                'zero_or_one'             => '\?',
                'zero_or_more_possessive' => '\*\+',
                'zero_or_more_lazy'       => '\*\?',
                'zero_or_more'            => '\*',
                'one_or_more_possessive'  => '\+\+',
                'one_or_more_lazy'        => '\+\?',
                'one_or_more'             => '\+',
                'exactly_n'               => '\{[0-9]+\}',
                'n_to_m_possessive'       => '\{[0-9]+,[0-9]+\}\+',
                'n_to_m_lazy'             => '\{[0-9]+,[0-9]+\}\?',
                'n_to_m'                  => '\{[0-9]+,[0-9]+\}',
                'n_or_more_possessive'    => '\{[0-9]+,\}\+',
                'n_or_more_lazy'          => '\{[0-9]+,\}\?',
                'n_or_more'               => '\{[0-9]+,\}',
                'alternation'             => '\|',
                'character'               => '\\\([aefnrt]|c[\x00-\x7f])',
                'dynamic_character'       => '\\\([0-7]{3}|x[0-9a-zA-Z]{2}|x{[0-9a-zA-Z]+})',
                'character_type'          => '\\\([CdDhHNRsSvVwWX]|[pP]{[^}]+})',
                'anchor'                  => '\\\([bBAZzG])|\^|\$',
                'match_point_reset'       => '\\\K',
                'literal'                 => '\\\.|.',
            ],
            'c'       => [
                'index:default' => '[\+\-]?\d+',
            ],
            'co'      => [
                '_comment:default' => '\)',
                'comment'          => '.*?(?=(?<!\\\)\))',
            ],
            'nc'      => [
                '_named_capturing:default' => '>',
                'capturing_name'           => '.+?(?=(?<!\\\)>)',
            ],
        ];
    }

    /**
     * @return iterable
     */
    public function samples(): iterable
    {
        yield 'little' => [__DIR__ . '/../samples/little.txt'];
        yield 'average' => [__DIR__ . '/../samples/average.txt'];
        yield 'large' => [__DIR__ . '/../samples/large.txt'];
    }

    /**
     * @ParamProviders({"samples"})
     * @param array $params
     */
    public function benchHoa(array $params): void
    {
        $tokens = $this->hoa->lexMe(\file_get_contents($params[0]), $this->hoaTokens);

        foreach ($tokens as $token) {
            //
        }
    }

    /**
     * @ParamProviders({"samples"})
     * @param array $params
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function benchRailt(array $params): void
    {
        $tokens = $this->railt->lex(File::fromPathname($params[0]));

        foreach ($tokens as $token) {
            //
        }
    }
}
