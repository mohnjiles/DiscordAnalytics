<?php

namespace App\Http\Controllers;

use App\ChannelStats;
use App\EventAnalytics;
use App\GameTime;
use App\StoredGameData;
use App\StoredVoiceData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function getChannelStats()
    {

        //$channelStats = DB::table('ChannelStats')->select(DB::raw("SELECT *"))->get();
        $channelStats = ChannelStats::select(DB::raw("sum(`count`) as theCount, user"))->groupBy("user")->orderBy("theCount", "desc")->get();

        $general = ChannelStats::where("channel", "general")->orderBy("count")->get();
        $nsfw = ChannelStats::where("channel", "nsfw")->orderBy("count")->get();
        $ffxiv = ChannelStats::where("channel", "ffxiv")->orderBy("count")->get();
        $art = ChannelStats::where("channel", "art_general")->orderBy("count")->get();
        $westworld = ChannelStats::where("channel", "westworld")->orderBy("count")->get();

        return view('channelstats', compact('channelStats', 'general', 'nsfw', 'ffxiv', 'art', 'westworld'));
    }

    public function getGameStats()
    {
        $overwatchSeconds = $this->getSecondsForGame("Overwatch");
        $ffxivSeconds = $this->getSecondsForGame("FINAL FANTASY XIV");
        $hearthstoneSeconds = $this->getSecondsForGame("Hearthstone");
        $hotsSeconds = $this->getSecondsForGame("Heroes of the Storm");
        $gw2Seconds = $this->getSecondsForGame("Guild Wars 2");



        $overwatch = $this->getGameTimeForUsers("Overwatch");
        $ffxiv = $this->getGameTimeForUsers("FINAL FANTASY XIV", "FINAL FANTASY XIV - A Realm Reborn");
        $lmi = $this->getGameTimeForUsers("LogMeIn Rescue Technician Console");
        $gw2 = $this->getGameTimeForUsers("Guild Wars 2");
        $hots = $this->getGameTimeForUsers("Heroes of the Storm");


        $overwatchReadable = $this->getReadableTime($overwatchSeconds);
        $ffxivReadable = $this->getReadableTime($ffxivSeconds);
        $hearthstoneReadable = $this->getReadableTime($hearthstoneSeconds);
        $hotsReadable = $this->getReadableTime($hotsSeconds);
        $gw2Readable = $this->getReadableTime($gw2Seconds);



        return view('gamestats',
            compact('overwatchSeconds', 'ffxivSeconds', 'hearthstoneSeconds',
                'overwatchReadable', 'ffxivReadable', 'hearthstoneReadable', 'overwatch', 'ffxiv',
                'lmi', 'hotsSeconds', 'hotsReadable', 'gw2Readable', 'gw2Seconds', 'gw2', 'hots'));
    }

    public function getVoiceChannelStats() {
        $hafSeconds = $this->getSecondsForVoiceChannel("High Air Flow");
        $generalSeconds = $this->getSecondsForVoiceChannel("General");
        $ffxivSeconds = $this->getSecondsForVoiceChannel("FFXIV");
        $afkSeconds = $this->getSecondsForVoiceChannel("AFK");


        $hafReadable = $this->getReadableTime($hafSeconds);
        $generalReadable = $this->getReadableTime($generalSeconds);
        $ffxivReadable = $this->getReadableTime($ffxivSeconds);
        $afkReadable = $this->getReadableTime($afkSeconds);

        return view ('voicechannels', compact('hafSeconds', 'hafReadable', 'generalReadable',
            'generalSeconds', 'ffxivReadable', 'ffxivSeconds', 'afkSeconds', 'afkReadable'));

    }

    private function getSecondsForGame($gameName, $secondaryGameName = null)
    {
        $storedData = StoredGameData::where("game_name", $gameName)->where("username", "overall")->first();

        if ($storedData != null && Carbon::parse($storedData->updated_at)->diffInHours(Carbon::now()) <= 2) {
            return $storedData->seconds;
        }

        $gameSeconds = 0;

        if ($secondaryGameName != null) {
            $presenceUpdates = EventAnalytics::where("event", "PRESENCE_UPDATE")
                ->where('id', '>', 1276)
                ->where(function ($q) use ($gameName, $secondaryGameName) {
                    $q->where("value", $gameName)
                        ->orWhere("value", $secondaryGameName);
                })->get();
        } else {
            $presenceUpdates = EventAnalytics::where("event", "PRESENCE_UPDATE")->where('id', '>', 1276)->where("value", $gameName)->get();
        }

        foreach ($presenceUpdates as $key => $presenceUpdate) {

            if ($key == 0 || $presenceUpdate->value == null || $presenceUpdate->timestamp == $presenceUpdates[$key - 1]->timestamp) continue;

            $nextThing = EventAnalytics::where("name", $presenceUpdate->name)
                ->where("event", "PRESENCE_UPDATE")
                ->where(function ($q) use ($gameName) {
                    $q->whereNull("value")
                        ->orWhere("value", $gameName);
                })
                ->where("id", ">", $presenceUpdate->id)
                ->orderBy("id")->first();

            if ($nextThing == null) continue;

            $diff = Carbon::parse($nextThing->timestamp)->diffInSeconds(Carbon::parse($presenceUpdate->timestamp));
            $gameSeconds += $diff;
        }

        if ($storedData != null) {
            $storedData->seconds = $gameSeconds;
            $storedData->save();
        } else {
            $sgd = new StoredGameData();
            $sgd->game_name = $gameName;
            $sgd->seconds = $gameSeconds;
            $sgd->username = "overall";
            $sgd->save();
        }

        return $gameSeconds;
    }

    private function getSecondsForUser($username, $gameName, $secondaryGameName = null)
    {
        $storedData = StoredGameData::where("game_name", $gameName)->where("username", $username)->first();

        if ($storedData != null && Carbon::parse($storedData->updated_at)->diffInHours(Carbon::now()) <= 2) {
            return $storedData->seconds;
        }

        $gameSeconds = 0;

        if ($secondaryGameName != null) {
            $presenceUpdates = EventAnalytics
                ::where("event", "PRESENCE_UPDATE")
                ->where('id', '>', 1276)
                ->where("name", $username)
                ->where(function ($q) use ($gameName, $secondaryGameName) {
                    $q->where("value", $gameName)
                        ->orWhere("value", $secondaryGameName);
                })
                ->get();
        } else {
            $presenceUpdates = EventAnalytics
                ::where("event", "PRESENCE_UPDATE")
                ->where('id', '>', 1276)
                ->where("value", $gameName)
                ->where("name", $username)
                ->get();
        }


        foreach ($presenceUpdates as $key => $presenceUpdate) {

            if ($key == 0 || $presenceUpdate->value == null || $presenceUpdate->timestamp == $presenceUpdates[$key - 1]->timestamp) continue;

            $nextThing = EventAnalytics
                ::where("name", $presenceUpdate->name)
                ->where("event", "PRESENCE_UPDATE")
                ->where(function ($q) use ($gameName) {
                    $q->whereNull("value")
                        ->orWhere("value", $gameName);
                })
                ->where("id", ">", $presenceUpdate->id)
                ->where("name", $username)
                ->orderBy("id")
                ->first();

            if ($nextThing == null) continue;

            $diff = Carbon::parse($nextThing->timestamp)->diffInSeconds(Carbon::parse($presenceUpdate->timestamp));
            $gameSeconds += $diff;

        }

        if ($storedData != null) {
            $storedData->seconds = $gameSeconds;
            $storedData->updated_at = Carbon::now();
            $storedData->save();
        } else {
            $sgd = new StoredGameData();
            $sgd->game_name = $gameName;
            $sgd->seconds = $gameSeconds;
            $sgd->username = $username;
            $sgd->save();
        }

        return $gameSeconds;
    }

    private function getGameTimeForUsers($gameName, $secondaryGameName = null)
    {
        $names = EventAnalytics::select("name")->groupBy("name")->get();

        $gameTimes = [];
        $gameTimeReadable = [];

        foreach ($names as $name) {
            $gameTimes[$name->name] = $this->getSecondsForUser($name->name, $gameName, $secondaryGameName);
            $gameTimeReadable[$name->name] = $this->getReadableTime($gameTimes[$name->name]);
        }

        $gameTime = new GameTime();
        $gameTime->readable = $gameTimeReadable;
        $gameTime->times = $gameTimes;

        return $gameTime;
    }

    /** Voice Channel Functions */

    private function getSecondsForVoiceChannel($channel)
    {
        $storedData = StoredVoiceData::where("channel", $channel)->where("username", "overall")->first();

        if ($storedData != null && Carbon::parse($storedData->updated_at)->diffInHours(Carbon::now()) <= 2) {
            return $storedData->seconds;
        }

        $gameSeconds = 0;

        $presenceUpdates = EventAnalytics::where("event", "VOICE_CHANNEL_JOIN")->where('id', '>', 1276)->where("value", $channel)->get();


        foreach ($presenceUpdates as $key => $presenceUpdate) {

            if ($key == 0 || $presenceUpdate->value == null || $presenceUpdate->timestamp == $presenceUpdates[$key - 1]->timestamp) continue;

            $nextThing = EventAnalytics::where("name", $presenceUpdate->name)
                ->where("event", "VOICE_CHANNEL_LEAVE")
                ->where("value", $channel)
                ->where("id", ">", $presenceUpdate->id)
                ->orderBy("id")->first();

            if ($nextThing == null) continue;

            $diff = Carbon::parse($nextThing->timestamp)->diffInSeconds(Carbon::parse($presenceUpdate->timestamp));
            $gameSeconds += $diff;
        }

        if ($storedData != null) {
            $storedData->seconds = $gameSeconds;
            $storedData->save();
        } else {
            $sgd = new StoredVoiceData();
            $sgd->channel = $channel;
            $sgd->seconds = $gameSeconds;
            $sgd->username = "overall";
            $sgd->save();
        }

        return $gameSeconds;
    }

    private function getSecondsForUserVoiceChannel($username, $voiceChannel)
    {
        $storedData = StoredVoiceData::where("channel", $voiceChannel)->where("username", $username)->first();

        if ($storedData != null && Carbon::parse($storedData->updated_at)->diffInHours(Carbon::now()) <= 2) {
            return $storedData->seconds;
        }

        $gameSeconds = 0;


        $voiceChannelJoins = EventAnalytics
            ::where("event", "VOICE_CHANNEL_JOIN")
            ->where('id', '>', 1276)
            ->where("name", $username)
            ->where("value", $voiceChannel)
            ->get();


        foreach ($voiceChannelJoins as $key => $channelJoin) {

            if ($key == 0 || $channelJoin->value == null || $channelJoin->timestamp == $channelJoin[$key - 1]->timestamp) continue;

            $nextThing = EventAnalytics
                ::where("event", "VOICE_CHANNEL_LEAVE")
                ->where("value", $channelJoin->value)
                ->where("id", ">", $channelJoin->id)
                ->where("name", $username)
                ->orderBy("id")
                ->first();

            if ($nextThing == null) continue;

            $diff = Carbon::parse($nextThing->timestamp)->diffInSeconds(Carbon::parse($channelJoin->timestamp));
            $gameSeconds += $diff;

        }

        if ($storedData != null) {
            $storedData->seconds = $gameSeconds;
            $storedData->updated_at = Carbon::now();
            $storedData->save();
        } else {
            $sgd = new StoredVoiceData();
            $sgd->channel = $voiceChannel;
            $sgd->seconds = $gameSeconds;
            $sgd->username = $username;
            $sgd->save();
        }

        return $gameSeconds;
    }

    /** End Voice Channel Functions */


    private function getReadableTime($seconds)
    {

        $ret = "";

        /*** get the days ***/
        $days = intval(intval($seconds) / (3600 * 24));
        if ($days > 0) {
            $ret .= "{$days}d ";
        }

        /*** get the hours ***/
        $hours = (intval($seconds) / 3600) % 24;
        if ($hours > 0) {
            $ret .= "{$hours}h ";
        }

        /*** get the minutes ***/
        $minutes = (intval($seconds) / 60) % 60;
        if ($minutes > 0) {
            $ret .= "{$minutes}m ";
        }

        /*** get the seconds ***/
        $seconds = intval($seconds) % 60;
        if ($seconds > 0) {
            $ret .= "{$seconds}s";
        }

        return $ret;

    }
}
