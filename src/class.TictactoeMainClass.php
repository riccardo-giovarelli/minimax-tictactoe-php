<?php

declare(strict_types=1);

/**
 * Tic-Tac-Toe AI Engine using Minimax with Alpha-Beta Pruning
 */
class TictactoeMainClass
{
    private const EMPTY_CELL = 0;
    private const PLAYER_MARKER = 1;
    private const AI_MARKER = 2;

    private const STATE_DRAW = 3;
    private const STATE_PLAYER_WIN = 5;
    private const STATE_ONGOING = 6;
    private const STATE_AI_WIN = 10;

    /** @var array<int, array<int, int>> */
    private array $board;

    /**
     * @param string $currentField Comma-separated string of 9 integers
     */
    public function __construct(string $currentField)
    {
        $cells = array_map('intval', explode(',', $currentField));

        if (count($cells) !== 9) {
            throw new InvalidArgumentException('Field must contain exactly 9 cells.');
        }

        // Convert the flat 9-element array into a 3x3 matrix
        $this->board = array_chunk($cells, 3);
    }

    /**
     * Return the best move coordinates for AI
     *
     * @return array{row: int, col: int}
     */
    public function findBestMove(): array
    {
        $bestVal = PHP_INT_MIN;
        $bestMove = ['row' => -1, 'col' => -1];

        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($this->board[$i][$j] === self::EMPTY_CELL) {
                    $this->board[$i][$j] = self::AI_MARKER;
                    $moveVal = $this->minimax($this->board, 0, false, PHP_INT_MIN, PHP_INT_MAX);
                    $this->board[$i][$j] = self::EMPTY_CELL;

                    if ($moveVal > $bestVal) {
                        $bestVal = $moveVal;
                        $bestMove = ['row' => $i, 'col' => $j];
                    }
                }
            }
        }

        return $bestMove;
    }

    /**
     * Minimax algorithm with Alpha-Beta pruning and depth scoring
     *
     * @param array<int, array<int, int>> $field
     */
    private function minimax(
        array &$field,
        int $depth,
        bool $isMax,
        int $alpha,
        int $beta
    ): int {
        $state = $this->checkCurrentState($field);

        if ($state === self::STATE_AI_WIN) {
            return 10 - $depth; // Reward faster wins
        }
        if ($state === self::STATE_PLAYER_WIN) {
            return $depth - 10; // Delay losses as much as possible
        }
        if ($state === self::STATE_DRAW) {
            return 0;
        }

        if ($isMax) {
            $best = PHP_INT_MIN;
            for ($i = 0; $i < 3; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    if ($field[$i][$j] === self::EMPTY_CELL) {
                        $field[$i][$j] = self::AI_MARKER;
                        $best = max($best, $this->minimax($field, $depth + 1, false, $alpha, $beta));
                        $field[$i][$j] = self::EMPTY_CELL;

                        $alpha = max($alpha, $best);
                        if ($beta <= $alpha) {
                            break 2; // Beta cutoff
                        }
                    }
                }
            }
            return $best;
        }

        $best = PHP_INT_MAX;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($field[$i][$j] === self::EMPTY_CELL) {
                    $field[$i][$j] = self::PLAYER_MARKER;
                    $best = min($best, $this->minimax($field, $depth + 1, true, $alpha, $beta));
                    $field[$i][$j] = self::EMPTY_CELL;

                    $beta = min($beta, $best);
                    if ($beta <= $alpha) {
                        break 2; // Alpha cutoff
                    }
                }
            }
        }
        return $best;
    }

    /**
     * Fast O(1) board evaluation without array allocations
     *
     * @param array<int, array<int, int>> $field
     */
    public function checkCurrentState(array $field): int
    {
        for ($i = 0; $i < 3; $i++) {
            // Check row $i
            if (
                $field[$i][0] !== self::EMPTY_CELL &&
                $field[$i][0] === $field[$i][1] &&
                $field[$i][1] === $field[$i][2]
            ) {
                return $field[$i][0] === self::AI_MARKER ? self::STATE_AI_WIN : self::STATE_PLAYER_WIN;
            }

            // Check column $i
            if (
                $field[0][$i] !== self::EMPTY_CELL &&
                $field[0][$i] === $field[1][$i] &&
                $field[1][$i] === $field[2][$i]
            ) {
                return $field[0][$i] === self::AI_MARKER ? self::STATE_AI_WIN : self::STATE_PLAYER_WIN;
            }
        }

        // Check main and anti-diagonal
        if ($field[1][1] !== self::EMPTY_CELL) {
            if (
                ($field[0][0] === $field[1][1] && $field[1][1] === $field[2][2]) ||
                ($field[0][2] === $field[1][1] && $field[1][1] === $field[2][0])
            ) {
                return $field[1][1] === self::AI_MARKER ? self::STATE_AI_WIN : self::STATE_PLAYER_WIN;
            }
        }

        // Check for remaining empty cells
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($field[$i][$j] === self::EMPTY_CELL) {
                    return self::STATE_ONGOING;
                }
            }
        }

        return self::STATE_DRAW;
    }
}