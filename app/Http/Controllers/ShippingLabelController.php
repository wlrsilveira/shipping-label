<?php

namespace App\Http\Controllers;

use App\Application\ShippingLabel\DTOs\CreateShippingLabelDTO;
use App\Application\ShippingLabel\DTOs\ShippingLabelResponseDTO;
use App\Application\ShippingLabel\Services\ShippingLabelService;
use App\Domain\ShippingLabel\ValueObjects\ShippingLabelStatus;
use App\Domain\ShippingLabel\ValueObjects\USState;
use App\Http\Requests\CreateShippingLabelRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShippingLabelController extends Controller
{
    public function __construct(
        private ShippingLabelService $shippingLabelService
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $status = $request->get('status');
        $userId = auth()->id();

        $labels = $this->shippingLabelService->getUserShippingLabels($userId, $perPage, $status);

        $labelsData = $labels->map(function ($label) {
            return ShippingLabelResponseDTO::fromDomain($label)->toArray();
        });

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $labelsData,
            $labels->total(),
            $labels->perPage(),
            $labels->currentPage(),
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );

        $statusLabel = null;
        if ($status) {
            try {
                $statusEnum = ShippingLabelStatus::from($status);
                $statusLabel = $statusEnum->getLabel();
            } catch (\ValueError $e) {
                $statusLabel = null;
            }
        }

        return Inertia::render('ShippingLabels/Index', [
            'labels' => $paginator->withPath($request->url())->withQueryString(),
            'filteredStatus' => $status,
            'filteredStatusLabel' => $statusLabel,
        ]);
    }

    public function create()
    {
        return Inertia::render('ShippingLabels/Create');
    }

    public function store(CreateShippingLabelRequest $request)
    {
        $dto = CreateShippingLabelDTO::fromArray($request->validated());
        $userId = auth()->id();

        $this->shippingLabelService->createShippingLabel($dto, $userId);

        return redirect()
            ->route('shipping-labels.index')
            ->with('success', 'Shipping label created successfully!');
    }

    public function show(Request $request, int $id)
    {
        $userId = auth()->id();

        $label = $this->shippingLabelService->getShippingLabelById($id, $userId);
        $labelDto = ShippingLabelResponseDTO::fromDomain($label);

        return Inertia::render('ShippingLabels/Show', [
            'label' => $labelDto->toArray(),
        ]);
    }

    public function destroy(int $id)
    {
        $userId = auth()->id();

        $this->shippingLabelService->deleteShippingLabel($id, $userId);

        return redirect()
            ->route('shipping-labels.index')
            ->with('success', 'Shipping label deleted successfully!');
    }

    public function usStates(): JsonResponse
    {
        $states = array_map(function (USState $state) {
            return [
                'value' => $state->value,
                'label' => $state->getFullName(),
            ];
        }, USState::cases());

        return response()->json($states);
    }
}

