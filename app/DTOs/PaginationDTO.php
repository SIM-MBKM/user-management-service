<?php

namespace App\DTOs;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PaginationDTO
{
    public array $data = [];
    public int $current_page;
    public string $first_page_url;
    public int $last_page;
    public string $last_page_url;
    public ?string $next_page_url;
    public int $per_page;
    public string $prev_page_url;
    public int $to;
    public int $total;
    public int $total_pages;

    /**
     * Create a new PaginationDTO instance from LengthAwarePaginator
     *
     * @param LengthAwarePaginator $paginator The paginator instance
     * @param array|Collection $data The transformed data items
     * @param Request|null $request The current request (optional)
     * @return self
     */
    public static function fromPaginator(LengthAwarePaginator $paginator, $data = null, ?Request $request = null): self
    {
        $instance = new self();

        // Use the paginator's transformed data or the provided data
        if ($data === null) {
            $instance->data = $paginator->items();
        } elseif ($data instanceof Collection) {
            $instance->data = $data->all();
        } else {
            $instance->data = $data;
        }

        $instance->current_page = $paginator->currentPage();
        $instance->last_page = $paginator->lastPage();
        $instance->per_page = $paginator->perPage();
        $instance->to = $paginator->count();
        $instance->total = $paginator->total();
        $instance->total_pages = $paginator->lastPage();

        // Handle URL generation
        if ($request) {
            $baseUrl = $request->url();
            $queryParams = $request->query();
            $limit = $request->query('limit', $paginator->perPage());

            // Generate pagination URLs
            $instance->first_page_url = self::getPageUrl($baseUrl, $queryParams, 1, $limit);
            $instance->last_page_url = self::getPageUrl($baseUrl, $queryParams, $paginator->lastPage(), $limit);
            $instance->next_page_url = $paginator->hasMorePages()
                ? self::getPageUrl($baseUrl, $queryParams, $paginator->currentPage() + 1, $limit)
                : null;
            $instance->prev_page_url = $paginator->onFirstPage()
                ? ""
                : self::getPageUrl($baseUrl, $queryParams, $paginator->currentPage() - 1, $limit);
        } else {
            // Default URLs if request is not provided
            $instance->first_page_url = $paginator->url(1);
            $instance->last_page_url = $paginator->url($paginator->lastPage());
            $instance->next_page_url = $paginator->nextPageUrl();
            $instance->prev_page_url = $paginator->previousPageUrl() ?? "";
        }

        return $instance;
    }

    /**
     * Convert the DTO to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'current_page' => $this->current_page,
            'first_page_url' => $this->first_page_url,
            'last_page' => $this->last_page,
            'last_page_url' => $this->last_page_url,
            'next_page_url' => $this->next_page_url,
            'per_page' => $this->per_page,
            'prev_page_url' => $this->prev_page_url,
            'to' => $this->to,
            'total' => $this->total,
            'total_pages' => $this->total_pages
        ];
    }

    /**
     * Check if the data is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Check if the data is not empty
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Generate page URL with proper parameters
     *
     * @param string $baseUrl The base URL
     * @param array $params Query parameters
     * @param int $page Page number
     * @param int $limit Items per page
     * @return string
     */
    private static function getPageUrl(string $baseUrl, array $params, int $page, int $limit): string
    {
        $params['page'] = $page;
        $params['limit'] = $limit;
        return $baseUrl . '?' . http_build_query($params);
    }
}
