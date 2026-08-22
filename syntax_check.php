<?php

$files = [
    'app/Domains/Chatbot/Services/ChatResponseHandlerRegistry.php',
    'app/Domains/Chatbot/Providers/ChatbotServiceProvider.php',
    'app/Domains/Chatbot/Actions/ProcessRuleBasedChatMessageAction.php',
    'app/Domains/Chatbot/Handlers/CancelWorkflowHandler.php',
    'app/Domains/Chatbot/Handlers/ResumeWorkflowHandler.php',
    'app/Domains/Chatbot/Handlers/CreateComplaintHandler.php',
    'app/Domains/Chatbot/Handlers/ContactRequestHandler.php',
    'app/Domains/CitizenWorkflows/Services/CitizenWorkflowEngine.php',
    'app/Domains/CitizenWorkflows/Services/WorkflowTrackingResolver.php',
    'app/Domains/CitizenWorkflows/Contracts/WorkflowTrackingResolverInterface.php',
    'app/Domains/CitizenWorkflows/DTOs/WorkflowTrackingData.php',
    'app/Domains/ContactRequests/Models/ContactRequest.php',
    'app/Domains/ContactRequests/Enums/ContactRequestStatus.php',
    'app/Domains/ContactRequests/DTOs/ContactRequestData.php',
    'app/Domains/ContactRequests/Contracts/ContactRequestRepositoryInterface.php',
    'app/Domains/ContactRequests/Repositories/EloquentContactRequestRepository.php',
    'app/Domains/ContactRequests/Actions/SubmitContactRequestAction.php',
    'app/Domains/ContactRequests/Providers/ContactRequestServiceProvider.php',
    'app/Domains/ContactRequests/Policies/ContactRequestPolicy.php',
    'app/Console/Commands/ChatbotWorkflowDiagnosticCommand.php',
];

echo "Checking PHP syntax...\n";
$errors = [];
foreach ($files as $file) {
    $fullPath = 'C:/Users/ahmed/idna-org/'.$file;
    if (! file_exists($fullPath)) {
        echo "MISSING: $file\n";

        continue;
    }
    $output = [];
    $returnVar = 0;
    exec('php -l '.escapeshellarg($fullPath), $output, $returnVar);
    if ($returnVar !== 0) {
        $errors[] = "$file: ".implode("\n", $output);
        echo "ERROR: $file\n".implode("\n", $output)."\n";
    } else {
        echo "OK: $file\n";
    }
}

if (empty($errors)) {
    echo "\nAll files syntax OK!\n";
    exit(0);
} else {
    echo "\nErrors found:\n";
    foreach ($errors as $e) {
        echo $e."\n\n";
    }
    exit(1);
}
