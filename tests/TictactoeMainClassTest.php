<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class TictactoeMainClassTest extends TestCase
{
    public function testThrowsExceptionOnInvalidBoardSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TictactoeMainClass('0,1,2,0,0');
    }

    public function testCheckCurrentStateDetectsAiWin(): void
    {
        // AI (2) wins on top row
        $game = new TictactoeMainClass('2,2,2,1,1,0,0,0,0');
        $board = [
            [2, 2, 2],
            [1, 1, 0],
            [0, 0, 0],
        ];

        $this->assertSame(10, $game->checkCurrentState($board));
    }

    public function testCheckCurrentStateDetectsPlayerWin(): void
    {
        // Player (1) wins on main diagonal
        $game = new TictactoeMainClass('1,2,0,2,1,0,0,0,1');
        $board = [
            [1, 2, 0],
            [2, 1, 0],
            [0, 0, 1],
        ];

        $this->assertSame(5, $game->checkCurrentState($board));
    }

    public function testCheckCurrentStateDetectsDraw(): void
    {
        $game = new TictactoeMainClass('1,2,1,1,2,2,2,1,1');
        $board = [
            [1, 2, 1],
            [1, 2, 2],
            [2, 1, 1],
        ];

        $this->assertSame(3, $game->checkCurrentState($board));
    }

    public function testCheckCurrentStateDetectsOngoingMatch(): void
    {
        $game = new TictactoeMainClass('1,2,0,0,0,0,0,0,0');
        $board = [
            [1, 2, 0],
            [0, 0, 0],
            [0, 0, 0],
        ];

        $this->assertSame(6, $game->checkCurrentState($board));
    }

    public function testAiTakesWinningMoveImmediately(): void
    {
        // Board:
        // [ 1, 2, 1 ]
        // [ 0, 2, 0 ]
        // [ 0, 0, 0 ]
        // AI (2) must play (2, 1) to win immediately on the middle column
        $game = new TictactoeMainClass('1,2,1,0,2,0,0,0,0');

        $this->assertSame(['row' => 2, 'col' => 1], $game->findBestMove());
    }

    public function testAiBlocksPlayerWinningMove(): void
    {
        // Board:
        // [ 1, 1, 0 ]
        // [ 0, 2, 0 ]
        // [ 0, 0, 0 ]
        // Player (1) threatens (0, 2) on top row; AI (2) must block it
        $game = new TictactoeMainClass('1,1,0,0,2,0,0,0,0');

        $this->assertSame(['row' => 0, 'col' => 2], $game->findBestMove());
    }

    public function testAiPrefersImmediateWinOverBlockingOrDelayedWin(): void
    {
        // Board:
        // [ 2, 2, 0 ] -> AI can win immediately at (0, 2)
        // [ 1, 1, 0 ] -> Player threatens (1, 2)
        // [ 1, 0, 0 ]
        $game = new TictactoeMainClass('2,2,0,1,1,0,1,0,0');

        $this->assertSame(['row' => 0, 'col' => 2], $game->findBestMove());
    }

    public function testAiPicksCenterWhenPlayerOpensInCorner(): void
    {
        // Standard optimal Tic-Tac-Toe theory: if Player opens at corner (0, 0),
        // the only non-losing response for AI is the center (1, 1).
        $game = new TictactoeMainClass('1,0,0,0,0,0,0,0,0');

        $this->assertSame(['row' => 1, 'col' => 1], $game->findBestMove());
    }
}