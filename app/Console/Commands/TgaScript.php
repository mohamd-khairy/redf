<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TgaScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tga:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        \Illuminate\Support\Facades\Schema::dropIfExists('alodhaibs');
        \Illuminate\Support\Facades\Schema::dropIfExists('analysis_chat_locations');
        \Illuminate\Support\Facades\Schema::dropIfExists('api_stop_pose');
        \Illuminate\Support\Facades\Schema::dropIfExists('api_key_admin_events');
        \Illuminate\Support\Facades\Schema::dropIfExists('api_key_access_events');
        \Illuminate\Support\Facades\Schema::dropIfExists('api_keys');
        \Illuminate\Support\Facades\Schema::dropIfExists('ashwered_complaints');
        \Illuminate\Support\Facades\Schema::dropIfExists('ashwered_maintainances');
        \Illuminate\Support\Facades\Schema::dropIfExists('ashwered_order_trackings');
        \Illuminate\Support\Facades\Schema::dropIfExists('ashwered_projects');
        \Illuminate\Support\Facades\Schema::dropIfExists('cars');
        \Illuminate\Support\Facades\Schema::dropIfExists('chambers');
        \Illuminate\Support\Facades\Schema::dropIfExists('chamber_files');
        \Illuminate\Support\Facades\Schema::dropIfExists('city_indust_chances');
        \Illuminate\Support\Facades\Schema::dropIfExists('city_comm_chances');
        \Illuminate\Support\Facades\Schema::dropIfExists('commercial_chance_pdfs');
        \Illuminate\Support\Facades\Schema::dropIfExists('commercial_chances');
        \Illuminate\Support\Facades\Schema::dropIfExists('user_cities');
        \Illuminate\Support\Facades\Schema::dropIfExists('cities');
        \Illuminate\Support\Facades\Schema::dropIfExists('hospital_translations');
        \Illuminate\Support\Facades\Schema::dropIfExists('hourly_packages');
        \Illuminate\Support\Facades\Schema::dropIfExists('industrial_chance_pdfs');
        \Illuminate\Support\Facades\Schema::dropIfExists('industrial_chances');
        \Illuminate\Support\Facades\Schema::dropIfExists('main_templates');
        \Illuminate\Support\Facades\Schema::dropIfExists('masjid_nabawis');
        \Illuminate\Support\Facades\Schema::dropIfExists('message_imara_settings');
        \Illuminate\Support\Facades\Schema::dropIfExists('messages');
        \Illuminate\Support\Facades\Schema::dropIfExists('order_status');
        \Illuminate\Support\Facades\Schema::dropIfExists('packages_counts');
        \Illuminate\Support\Facades\Schema::dropIfExists('packages');
        \Illuminate\Support\Facades\Schema::dropIfExists('petromins');
        \Illuminate\Support\Facades\Schema::dropIfExists('rcj_chances');
        \Illuminate\Support\Facades\Schema::dropIfExists('refd_templates');
        \Illuminate\Support\Facades\Schema::dropIfExists('refd_tickets');
        \Illuminate\Support\Facades\Schema::dropIfExists('saf_complaints');
        \Illuminate\Support\Facades\Schema::dropIfExists('saf_versions');
        \Illuminate\Support\Facades\Schema::dropIfExists('semasco');
        \Illuminate\Support\Facades\Schema::dropIfExists('send_templates');
        \Illuminate\Support\Facades\Schema::dropIfExists('swcc_notifications');
        \Illuminate\Support\Facades\Schema::dropIfExists('template_messages');
        \Illuminate\Support\Facades\Schema::dropIfExists('suggestions_and_complaints');
        \Illuminate\Support\Facades\Schema::dropIfExists('tickets');
        \Illuminate\Support\Facades\Schema::dropIfExists('ticket_police');
        \Illuminate\Support\Facades\Schema::dropIfExists('nationalities');

        DB::table('answers')
            ->whereNotIn('user_id', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('id', 87)
                    ->orWhere('primary_id', 87)
                    ->orWhere('is_admin', 1);
            })
            ->delete();

        DB::table('categories')->where('chat_id', '!=', 141)->delete();

        DB::table('chat_statuses')->where('chat_id', '!=', 141)->delete();

        DB::table('chat_room_users')
            ->whereIn('chat_room_id', function ($query) {
                $query->select('id')
                    ->from('chat_rooms')
                    ->where('chat_id', '!=', 141)
                    ->orWhereNull('chat_id');
            })
            ->delete();

        DB::table('chat_histories')->where('chat_id', '!=', 141)->delete();

        DB::table('chat_rooms')->where('chat_id', '!=', 141)->orWhereNull('chat_id')->delete();

        DB::table('chat_rooms')->where('chat_id', '!=', 141)->orWhereNull('chat_id')->delete();

        DB::table('errors')->where('chat_id', '!=', 141)->orWhereNull('chat_id')->delete();

        DB::table('faq')
            ->where(function ($query) {
                $query->whereNotIn('user_id', function ($subquery) {
                    $subquery->select('id')
                        ->from('users')
                        ->where('id', 87)
                        ->orWhere('primary_id', 87)
                        ->orWhere('is_admin', 1);
                })->orWhereNull('user_id');
            })
            ->delete();

        DB::table('guests')->where('chat_id', '!=', 141)->orWhereNull('chat_id')->delete();

        DB::table('limits')->where('chat_id', '!=', 141)->orWhereNull('chat_id')->delete();

        DB::table('live_chats')
            ->whereIn('chat_room', function ($query) {
                $query->select('id')
                    ->from('chat_rooms')
                    ->where('chat_id', '!=', 141)
                    ->orWhereNull('chat_id');
            })
            ->OrWhereNotIn('user_id', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('id', 87)
                    ->orWhere('primary_id', 87)
                    ->orWhere('is_admin', 1);
            })
            ->delete();

        DB::table('live_chat_supports')
            ->where('chat_id', '!=', 141)
            ->orWhereNotIn('chat_room_id', function ($query) {
                $query->select('id')->from('chat_rooms');
            })
            ->delete();

        DB::table('locations')->where('chat_id', '!=', 141)->delete();

        DB::table('not_bot_response_answers')->where('chat_id', '!=', 141)->delete();

        DB::table('questions')->where('chat_id', '!=', 141)->delete();

        DB::table('permission_user')
            ->where(function ($query) {
                $query->whereNotIn('user_id', function ($subquery) {
                    $subquery->select('id')
                        ->from('users')
                        ->where('id', 87)
                        ->orWhere('primary_id', 87)
                        ->orWhere('is_admin', 1);
                })->orWhereNull('user_id');
            })
            ->delete();

        DB::table('questions_answers')
            ->where(function ($query) {
                $query->whereNotIn('user_id', function ($subquery) {
                    $subquery->select('id')
                        ->from('users')
                        ->where('id', 87)
                        ->orWhere('primary_id', 87)
                        ->orWhere('is_admin', 1);
                })->orWhereNull('user_id');
            })
            ->delete();

        DB::table('q_ans')->where('chat_id', '!=', 141)->delete();

        DB::table('rates')->where('chat_id', '!=', 141)->delete();

        DB::table('settings')
            ->whereNotIn('user_id', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('id', 87)
                    ->orWhere('primary_id', 87)
                    ->orWhere('is_admin', 1);
            })
            ->delete();

        DB::table('user_subscriptions')
            ->whereNotIn('user_id', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('id', 87)
                    ->orWhere('primary_id', 87)
                    ->orWhere('is_admin', 1);
            })
            ->delete();

        DB::table('chats')->where('id', '!=', 141)->delete();

        DB::statement('CREATE TEMPORARY TABLE KeepUsers AS SELECT id FROM users WHERE primary_id = 87 OR id = 87 OR is_admin = 1');

        DB::table('users')
            ->whereNotIn('id', function ($query) {
                $query->select('id')->from('KeepUsers');
            })
            ->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return Command::SUCCESS;
    }
}
