# Minimax for Tic-Tac-Toe
A modern **PHP** implementation of the Minimax algorithm with **Alpha-Beta Pruning** for the Tic-Tac-Toe game.

## Table of contents
- [Minimax for Tic-Tac-Toe](#minimax-for-tic-tac-toe)
  - [Table of contents](#table-of-contents)
  - [Overview](#overview)
  - [Prerequisites](#prerequisites)
  - [Class structure](#class-structure)
    - [Board representation](#board-representation)
    - [Methods](#methods)
  - [Usage :point\_right:](#usage-point_right)
  - [References](#references)
  - [Author](#author)
  - [Contacts](#contacts)
  - [License](#license)

## Overview
This project provides a strict-typed **PHP** implementation of the recursive **Minimax** decision algorithm applied to Tic-Tac-Toe.

Key features:
- **Alpha-Beta Pruning:** Cuts off suboptimal branches early, reducing recursive evaluations by over 90%.
- **Depth-aware scoring:** Rewards faster wins (`10 - $depth`) and delays potential losses (`$depth - 10`).

## Prerequisites
- PHP >= 8.0

## Class structure

### Board representation
The constructor accepts a comma-separated string of 9 integers representing the 3x3 grid row by row:
- `0`: Empty cell
- `1`: Player marker
- `2`: AI marker

### Methods
The `TictactoeMainClass` exposes two public methods and one internal recursive method:

- `findBestMove(): array` — Evaluates the current board and returns the optimal coordinates (`['row' => int, 'col' => int]`) for the AI turn.
- `checkCurrentState(array $field): int` — Evaluates the board state (`10` if AI wins, `5` if Player wins, `3` for a draw, `6` if the match is still ongoing).
- `minimax(array &$field, int $depth, bool $isMax, int $alpha, int $beta): int` — *(Private)* Executes the recursive Minimax search with in-place backtracking and Alpha-Beta pruning.

## Usage :point_right:

```php
<?php

require_once 'TictactoeMainClass.php';

// Current 3x3 board state:
// [ 1, 2, 1 ]
// [ 0, 2, 0 ]
// [ 0, 0, 0 ]
$currentField = '1,2,1,0,2,0,0,0,0';

$game = new TictactoeMainClass($currentField);
$bestMove = $game->findBestMove();

// Output: ['row' => 2, 'col' => 1] (AI completes the middle column)
print_r($bestMove);
```

## References
- [Minimax Algorithm — Wikipedia](https://en.wikipedia.org/wiki/Minimax)
- [Alpha-Beta Pruning — Wikipedia](https://en.wikipedia.org/wiki/Alpha%E2%80%93beta_pruning)
- [Tic-Tac-Toe — Wikipedia](https://en.wikipedia.org/wiki/Tic-tac-toe)

## Author
Riccardo Giovarelli

## Contacts
[![LinkedIn](https://img.shields.io/badge/Linkedin-%230077B5.svg?logo=linkedin&logoColor=white)](https://linkedin.com/in/riccardo-giovarelli) [![github](https://img.shields.io/badge/github-181717.svg?logo=github&logoColor=white)](https://github.com/riccardo-giovarelli)

## License
[MIT License](https://opensource.org/license/mit/)