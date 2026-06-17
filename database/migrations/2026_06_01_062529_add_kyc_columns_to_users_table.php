<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Statut global KYC (EN_ATTENTE, EN_COURS, VALIDE, REJETE)
            $table->enum('kyc_status', ['NOT_STARTED', 'STEP1_PENDING', 'STEP1_REJECTED', 'STEP1_VALIDATED', 'STEP2_PENDING', 'STEP2_REJECTED', 'STEP2_VALIDATED', 'STEP3_PENDING', 'STEP3_REJECTED', 'STEP3_VALIDATED', 'STEP4_PENDING', 'STEP4_REJECTED', 'STEP4_VALIDATED', 'COMPLETED'])->default('NOT_STARTED');
            // Données de chaque étape
            $table->string('identity_first_name')->nullable();
            $table->string('identity_last_name')->nullable();
            $table->date('identity_birth_date')->nullable();
            $table->string('identity_birth_place')->nullable();
            $table->string('identity_nationality')->nullable();
            
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_country')->nullable();
            $table->string('proof_of_address_path')->nullable();
            
            $table->string('id_document_type')->nullable(); // CNI, PASSEPORT, PERMIS
            $table->string('id_document_number')->nullable();
            $table->string('id_document_front_path')->nullable();
            $table->string('id_document_back_path')->nullable();
            
            $table->string('selfie_with_id_path')->nullable();
            $table->string('additional_document_path')->nullable();
            
            $table->text('kyc_rejection_reason')->nullable();
            $table->timestamp('kyc_submitted_at')->nullable();
            $table->timestamp('kyc_validated_at')->nullable();
            $table->foreignId('kyc_validated_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'kyc_status', 'identity_first_name', 'identity_last_name', 'identity_birth_date', 'identity_birth_place', 'identity_nationality',
                'address_street', 'address_city', 'address_postal_code', 'address_country', 'proof_of_address_path',
                'id_document_type', 'id_document_number', 'id_document_front_path', 'id_document_back_path',
                'selfie_with_id_path', 'additional_document_path', 'kyc_rejection_reason', 'kyc_submitted_at', 'kyc_validated_at', 'kyc_validated_by'
            ]);
        });
    }
};