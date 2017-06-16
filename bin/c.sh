#!/bin/bash
rm -rf src/log/{*.log,*.html} src/temp/{btfj.dat,cache/*,*.txt} tests/tmp/{*.txt,*.log,*.html,cache/*}
echo -e "\e[32mTemp & logs cleaned\e[39m"