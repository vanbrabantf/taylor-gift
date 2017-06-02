<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\BotManFactory;
use Mpociot\BotMan\Cache\ArrayCache;
use Mpociot\BotMan\Drivers\TelegramDriver;

class BotManController extends Controller
{
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
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function listen(Request $request)
    {
        /** @var BotMan $telegramBot */
        $telegramBot = app('botman');
        $telegramBot->verifyServices(env('TELEGRAM_TOKEN'));

        /** @var BotMan $botman */
        $botman = BotManFactory::create([
            'slack_token' => env('SLACK_TOKEN')
        ], new ArrayCache());

        // give the bot something to listen for.
        $botman->hears('taylorgift send {message}', function (BotMan $bot, $message) use ($telegramBot) {
            $telegramBot->say($message, '331671427', TelegramDriver::class); // Maarten
            $telegramBot->say($message, '331994553', TelegramDriver::class); // Frederick
            $telegramBot->say($message, '315577430', TelegramDriver::class); // Andreas
            $telegramBot->say($message, '396041528', TelegramDriver::class); // AJ
            $bot->reply(':heart: Successfully send message!');
        });

        $botman->listen();

        if ($request->get('type') === 'url_verification') {
            return $request->get('challenge');
        }
    }

    /**
     * @param Request $request
     */
    public function telegramListen(Request $request)
    {
        logger($request->all());
        /** @var BotMan $telegramBot */
        $telegramBot = app('botman');
        $telegramBot->verifyServices(env('TELEGRAM_TOKEN'));

        /** @var BotMan $slackBot */
        $slackBot = BotManFactory::create([
            'slack_token' => env('SLACK_TOKEN')
        ], new ArrayCache());

        // Simple respond method
        $telegramBot->hears('{message}', function (BotMan $bot, $message) use ($slackBot) {
            $slackBot->say($bot->getUser()->getFirstName() . ': ' . $message, '#the-gift');
        });

        $telegramBot->listen();
    }
}
