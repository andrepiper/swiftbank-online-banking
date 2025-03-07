@extends('layouts.app')

@section('title', 'KYC Documents')

@section('content')
<div class="kyc-container">
    <div class="page-header">
        <h1>KYC Documents</h1>
        <p>Manage your identity verification documents</p>
    </div>

    <div class="kyc-status-card">
        <div class="status-icon verified">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="status-details">
            <h2>Verification Status: <span class="status verified">Verified</span></h2>
            <p>Your identity has been verified. You have full access to all SwiftBank services.</p>
        </div>
    </div>

    <div class="documents-section">
        <div class="section-header">
            <h2>Your Documents</h2>
            <button class="btn-upload">
                <i class="fas fa-upload"></i> Upload New Document
            </button>
        </div>

        <div class="documents-grid">
            <div class="document-card">
                <div class="document-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="document-details">
                    <h3>National ID Card</h3>
                    <p>Uploaded on: Jan 15, 2023</p>
                    <span class="document-status verified">Verified</span>
                </div>
                <div class="document-actions">
                    <button class="btn-icon" title="View Document">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Download Document">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>

            <div class="document-card">
                <div class="document-icon">
                    <i class="fas fa-passport"></i>
                </div>
                <div class="document-details">
                    <h3>Passport</h3>
                    <p>Uploaded on: Feb 3, 2023</p>
                    <span class="document-status verified">Verified</span>
                </div>
                <div class="document-actions">
                    <button class="btn-icon" title="View Document">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Download Document">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>

            <div class="document-card">
                <div class="document-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="document-details">
                    <h3>Proof of Address</h3>
                    <p>Uploaded on: Feb 10, 2023</p>
                    <span class="document-status verified">Verified</span>
                </div>
                <div class="document-actions">
                    <button class="btn-icon" title="View Document">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Download Document">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="verification-requirements">
        <h2>Verification Requirements</h2>
        <div class="requirements-list">
            <div class="requirement-item completed">
                <div class="requirement-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="requirement-details">
                    <h3>Government-issued ID</h3>
                    <p>National ID card, passport, or driver's license</p>
                </div>
            </div>

            <div class="requirement-item completed">
                <div class="requirement-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="requirement-details">
                    <h3>Proof of Address</h3>
                    <p>Utility bill, bank statement, or official letter (not older than 3 months)</p>
                </div>
            </div>

            <div class="requirement-item completed">
                <div class="requirement-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="requirement-details">
                    <h3>Selfie Verification</h3>
                    <p>A clear photo of yourself holding your ID document</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .kyc-container {
        width: 100%;
        max-width: 100%;
        padding-bottom: 30px;
    }

    .page-header {
        margin-bottom: 25px;
    }

    .page-header h1 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .page-header p {
        color: #888;
        margin: 0;
    }

    .kyc-status-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }

    .status-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        font-size: 24px;
        color: white;
    }

    .status-icon.verified {
        background-color: #00c853;
    }

    .status-icon.pending {
        background-color: #ff9800;
    }

    .status-icon.rejected {
        background-color: #ff3d00;
    }

    .status-details h2 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .status-details p {
        color: #666;
        margin: 0;
    }

    .status {
        font-weight: 600;
    }

    .status.verified {
        color: #00c853;
    }

    .status.pending {
        color: #ff9800;
    }

    .status.rejected {
        color: #ff3d00;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-header h2 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .btn-upload {
        background-color: #0066ff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 15px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-upload:hover {
        background-color: #0052cc;
    }

    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .document-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
    }

    .document-icon {
        width: 50px;
        height: 50px;
        background-color: #f5f7fb;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 20px;
        color: #0066ff;
    }

    .document-details {
        flex: 1;
    }

    .document-details h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .document-details p {
        font-size: 12px;
        color: #888;
        margin: 0 0 5px 0;
    }

    .document-status {
        font-size: 12px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
        display: inline-block;
    }

    .document-status.verified {
        background-color: #e8f5e9;
        color: #00c853;
    }

    .document-status.pending {
        background-color: #fff8e1;
        color: #ff9800;
    }

    .document-status.rejected {
        background-color: #ffebee;
        color: #ff3d00;
    }

    .document-actions {
        display: flex;
        gap: 10px;
    }

    .btn-icon {
        background: none;
        border: none;
        color: #555;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
    }

    .btn-icon:hover {
        color: #0066ff;
    }

    .verification-requirements {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .verification-requirements h2 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .requirements-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .requirement-item {
        display: flex;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .requirement-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .requirement-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 14px;
        color: white;
    }

    .requirement-item.completed .requirement-icon {
        background-color: #00c853;
    }

    .requirement-item.pending .requirement-icon {
        background-color: #ff9800;
    }

    .requirement-details h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .requirement-details p {
        font-size: 14px;
        color: #666;
        margin: 0;
    }

    @media (max-width: 768px) {
        .documents-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
