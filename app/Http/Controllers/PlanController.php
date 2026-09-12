<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\PlanLegalTerm;
use App\Models\PlanPeriod;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return redirect()->route('configuracion');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'max_artworks' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
            'periods' => 'array',
            'periods.*.number' => 'required_with:periods.*|integer|min:1',
            'periods.*.period' => 'required_with:periods.*|in:monthly,quarterly,semiannual,annual',
            'periods.*.price' => 'required_with:periods.*|numeric|min:0',
            'features' => 'array',
            'features.*.description' => 'required_with:features.*|string',
            'legal_terms' => 'array',
            'legal_terms.*.description' => 'required_with:legal_terms.*|string',
            'legal_terms.*.link' => 'nullable|url',
        ]);

        $plan = Plan::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
            'max_artworks' => $validated['max_artworks'] ?? null,
            'is_free' => $validated['is_free'] ?? false,
        ]);

        $this->syncRelations($plan, $validated);

        return redirect()->route('configuracion', ['tab' => 'plans'])
            ->with('status', 'Plan creado exitosamente.');
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'max_artworks' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
            'periods' => 'array',
            'periods.*.number' => 'required_with:periods.*|integer|min:1',
            'periods.*.period' => 'required_with:periods.*|in:monthly,quarterly,semiannual,annual',
            'periods.*.price' => 'required_with:periods.*|numeric|min:0',
            'features' => 'array',
            'features.*.description' => 'required_with:features.*|string',
            'legal_terms' => 'array',
            'legal_terms.*.description' => 'required_with:legal_terms.*|string',
            'legal_terms.*.link' => 'nullable|url',
        ]);

        $plan->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
            'max_artworks' => $validated['max_artworks'] ?? null,
            'is_free' => $validated['is_free'] ?? false,
        ]);

        $this->syncRelations($plan, $validated);

        return redirect()->route('configuracion', ['tab' => 'plans'])
            ->with('status', 'Plan actualizado exitosamente.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('configuracion', ['tab' => 'plans'])
            ->with('status', 'Plan eliminado exitosamente.');
    }

    private function syncRelations(Plan $plan, array $validated): void
    {
        // Preservar paddle ids existentes al actualizar periodos.
        $existingPeriods = $plan->periods()->get()->keyBy(fn ($p) => $p->number.'-'.$p->period);

        $plan->periods()->delete();
        foreach ($validated['periods'] ?? [] as $periodData) {
            if (empty($periodData['number']) || empty($periodData['period'])) {
                continue;
            }
            $key = $periodData['number'].'-'.$periodData['period'];
            $old = $existingPeriods->get($key);

            $plan->periods()->create(array_merge($periodData, [
                'paddle_product_id' => $old?->paddle_product_id,
                'paddle_price_id' => $old?->paddle_price_id,
            ]));
        }

        $plan->features()->delete();
        foreach ($validated['features'] ?? [] as $feature) {
            if (empty($feature['description'])) {
                continue;
            }
            $plan->features()->create($feature);
        }

        $plan->legalTerms()->delete();
        foreach ($validated['legal_terms'] ?? [] as $term) {
            if (empty($term['description'])) {
                continue;
            }
            $plan->legalTerms()->create($term);
        }
    }
}
