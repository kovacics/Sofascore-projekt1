<?php


namespace src\View;


use src\model\Comment;
use src\model\User;
use src\template\TemplateEngine;

class CommentView implements View
{
    private Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }


    function getHtml():string
    {
        $commentView = new TemplateEngine("src/View/components/comment.html");
        $commentView->addParam("comment", $this->advanceText($this->comment->text));

        $user = __(User::get($this->comment->userId)->email)?? "Unknown";
        $commentView->addParam("author", $user);

        return $commentView->getHtml();
    }

    private function advanceText(string $input): string
    {
        $input = preg_replace("~\\*(.+?)\\*~", "<strong>$1</strong>", $input);
        $input = preg_replace("~_(.+?)_~", "<u>$1</u>", $input);
        return $input;
    }
}