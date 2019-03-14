# Lexers Benchmarks

## Results 

**Here:** [https://travis-ci.org/SerafimArts/lexer-benchmarks](https://travis-ci.org/SerafimArts/lexer-benchmarks)

## Sources

**Here:** [./src/Benchmark.php](./src/Benchmark.php)

## Manual Run

- `composer install`
- `vendor/bin/phpbench run src/Benchmark.php --report='generator: "table", compare: "set", cols: ["subject", "mean"]'`

## Other

- Lexical tokens are taken from [hoa\regex](https://github.com/hoaproject/Regex/blob/master/Source/Grammar.pp)
- This package implemented for the issue [hoa\compiler issue/81](https://github.com/hoaproject/Compiler/issues/81)
