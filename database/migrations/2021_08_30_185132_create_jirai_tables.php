<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJiraiTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('jirai_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color');
            $table->timestamps();
        });


        Schema::create('jirai_tag_role', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedBigInteger('jirai_tag_id');
            $table->primary(['role_id', 'jirai_tag_id']);
        });

        Schema::table('jirai_tag_role', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('jirai_tag_id')->references('id')->on('jirai_tags');
        });


        Schema::create('jirai_issues', function (Blueprint $table) {
            $table->id();
            $table->boolean('closed');
            $table->string('type');
            $table->string('title');
            $table->longText('message');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });

        Schema::table('jirai_issues', function (Blueprint $table) {
            $table->foreign("user_id")->references('id')->on('users');
        });

        Schema::create('jirai_issue_jirai_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('jirai_issue_id');
            $table->unsignedBigInteger('jirai_tag_id');
        });

        Schema::table('jirai_issue_jirai_tag', function (Blueprint $table) {
            $table->foreign('jirai_issue_id')->references('id')->on('jirai_issues');
            $table->foreign('jirai_tag_id')->references('id')->on('jirai_tags');
        });


        Schema::create('jirai_changelogs', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->longText('message');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });

        Schema::create('jirai_messages', function (Blueprint $table) {
            $table->id();
            $table->longText('message')->nullable();
            $table->unsignedBigInteger('jirai_issue_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('referenced_jirai_changelog_id')->nullable();
            $table->timestamps();
        });

        Schema::table('jirai_messages', function (Blueprint $table) {
            $table->foreign('jirai_issue_id')->references('id')->on('jirai_issues');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('referenced_jirai_changelog_id')->references('id')->on('jirai_changelogs')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jirai_issue_jirai_tag');
        Schema::dropIfExists('jirai_tag_role');
        Schema::dropIfExists('jirai_tags');
        Schema::dropIfExists('jirai_messages');
        Schema::dropIfExists('jirai_issues');
        Schema::dropIfExists('jirai_changelogs');
    }
}
