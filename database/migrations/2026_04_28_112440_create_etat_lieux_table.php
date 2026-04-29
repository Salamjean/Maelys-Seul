<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etat_lieux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bien_id')->constrained('biens')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('admins')->onDelete('set null'); // Agent de l'agence (role agent ou admin)
            $table->enum('type', ['entree', 'sortie']);
            $table->string('statut')->default('en_attente'); // en_attente, otp_verifie, termine
            $table->string('otp_code')->nullable();
            
            // Informations complémentaires
            $table->string('compteur_eau')->nullable();
            $table->string('compteur_electricite')->nullable();
            $table->integer('nombre_cles')->nullable();

            $table->text('remarques_globales')->nullable();
            $table->string('document_pdf')->nullable();
            $table->date('date_etat_lieux')->nullable();
            
            $table->timestamps();
        });

        Schema::create('etat_lieux_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etat_lieu_id')->constrained('etat_lieux')->onDelete('cascade');
            $table->string('piece'); // ex: Salon, Chambre 1, Toilette 1, Cuisine
            $table->string('element'); // ex: Murs, Sol, Plafond, Portes, Fenêtres, Équipements
            $table->string('etat')->nullable(); // bon, moyen, mauvais
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etat_lieux_details');
        Schema::dropIfExists('etat_lieux');
    }
};
