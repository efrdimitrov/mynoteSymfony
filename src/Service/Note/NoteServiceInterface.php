<?php


namespace App\Service\Note;

interface NoteServiceInterface
{
    public function mainNotes(): array;
    public function othersNotes(): array;
    public function allMainNotes();
}