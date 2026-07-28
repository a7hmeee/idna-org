<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('jobs.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'departmentId' => ['nullable', 'integer', 'exists:departments,id'],
            'jobNumber' => ['nullable', 'string', 'max:100'],
            'employmentType' => ['required', 'string', 'in:full_time,part_time,contract,temporary,volunteer,internship'],
            'location' => ['required', 'string', 'max:255'],
            'salary' => ['nullable', 'string', 'max:100'],
            'vacancies' => ['nullable', 'integer', 'min:1', 'max:999'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'array', 'min:1'],
            'requirements.*' => ['string', 'max:500'],
            'responsibilities' => ['required', 'array', 'min:1'],
            'responsibilities.*' => ['string', 'max:500'],
            'benefits' => ['nullable', 'array'],
            'benefits.*' => ['string', 'max:500'],
            'requiredDocuments' => ['required', 'array', 'min:1'],
            'requiredDocuments.*' => ['string', 'max:500'],
            'applicationMethod' => ['required', 'string', 'in:external_link,email,phone,office,download_form'],
            'applicationUrl' => ['nullable', 'url', 'max:500'],
            'applicationEmail' => ['nullable', 'email', 'max:255'],
            'applicationPhone' => ['nullable', 'string', 'max:50'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            'publishAt' => ['required', 'date'],
            'closingAt' => ['required', 'date', 'after_or_equal:publishAt'],
            'status' => ['nullable', 'string', 'in:draft,published,closed,archived'],
            'isPublic' => ['nullable', 'boolean'],
            'isFeatured' => ['nullable', 'boolean'],
        ];
    }
}
