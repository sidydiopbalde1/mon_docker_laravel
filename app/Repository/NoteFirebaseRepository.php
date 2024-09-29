<?php
namespace App\Repository;
use App\Facades\PromotionFirebaseFacade as Promotions;
class NoteFirebaseRepository 
{
    protected $firebaseNode;

    public function __construct()
    {
        $this->firebaseNode = 'promotions';
    }

}