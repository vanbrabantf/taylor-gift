<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\BotManFactory;
use React\EventLoop\Factory;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
        $botman->verifyServices(env('SLACK_TOKEN'));
        $botman->say('hai', 'frederick');

        // Simple respond method
        $botman->hears('Hello', function (BotMan $bot) {
            $bot->reply('Hi there :)');
        });

        $botman->listen();
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }

    /**
     * Loaded through routes/botman.php
     */
    public function listen()
    {
        $botman = BotManFactory::create([
            'slack_token' => env('SLACK_TOKEN')
        ]);

        // give the bot something to listen for.
        $botman->hears('hello', function (BotMan $bot) {
            $bot->reply('Hello yourself.');
        });

        $botman->listen();
    }
}
