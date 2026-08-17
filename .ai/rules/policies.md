---
paths:
  - 'app/Policies/**'
---

# Policies

## Gate::authorize silently denies for missing policy methods
If a Policy class has no method matching the ability name, `Gate::authorize()` / `#[Authorize]` denies (403) silently instead of throwing "method not found" — `Gate::resolvePolicyCallback()` just returns `false` when `is_callable([$policy, $method])` is false. This looks identical to a real permission-denial bug (wrong permission, stale cache, middleware order), so when an authorize check unexpectedly denies, verify the ability method actually exists on the policy class before debugging permissions/middleware/cache.
