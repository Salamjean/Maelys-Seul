<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Renommer 'name' en 'nom' if needed, but keeping 'name' as full name might be easier.
            // However user wants Nom + Prenoms.
            $table->string('prenoms')->nullable()->after('name');
            $table->string('contact')->nullable();
            $table->string('profession')->nullable();
            $table->text('adresse')->nullable();
            $table->string('piece_identite')->nullable(); // File path
            $table->string('attestation_travail')->nullable(); // File path
            $table->string('bulletin_salaire')->nullable(); // File path
            $table->string('doc_extra_1')->nullable(); // File path
            $table->string('doc_extra_2')->nullable(); // File path
            $table->string('doc_extra_3')->nullable(); // File path
            $table->string('contrat_bail')->nullable(); // File path
            $table->foreignId('bien_id')->nullable()->constrained('biens')->onDelete('set null');
            $table->string('role')->default('locataire');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'prenoms', 'contact', 'profession', 'adresse', 
                'piece_identite', 'attestation_travail', 'bulletin_salaire',
                'doc_extra_1', 'doc_extra_2', 'doc_extra_3',
                'contrat_bail', 'bien_id', 'role'
            ]);
        });
    }
};
