# Lexers Benchmarks

## Results 

**Here:** [https://travis-ci.org/SerafimArts/lexer-benchmarks](https://travis-ci.org/SerafimArts/lexer-benchmarks)

## Sources

**Here:** [./src/Benchmark.php](./src/Benchmark.php)

## Manual Run

1) `composer install`
2) `vendor/bin/phpbench run src/Benchmark.php --report='generator: "table", compare: "set", cols: ["subject", "mean"]'`
