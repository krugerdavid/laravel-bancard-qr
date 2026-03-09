# Change Log

## 2.0.0 - 2025-03-09

### Added
* `BancardQRManager` class for dependency injection and testability
* `BancardQR` Facade for cleaner usage
* Configurable HTTP timeout via `BANCARDQR_TIMEOUT` env
* `mergeConfigFrom` in ServiceProvider so config works without publishing
* Publish tag `bancardqr-config` for config publishing
* Full test suite with Pest (Unit + Feature)
* GitHub Actions CI for PHP 8.2-8.4 and Laravel 11-12

### Changed
* PHP requirement: ^8.1 (was ^8.0)
* Added `illuminate/support` as dependency
* `minimum-stability`: stable (was dev)
* ServiceProvider: `mergeConfigFrom` before publish, return type `void`
* Errors now throw `\Exception` instead of returning decoded JSON
* `revert()` throws `InvalidArgumentException` for empty hook alias

### Fixed
* **Bug**: `revert()` used `config('bancardqr.commerce.code')` and `config('bancardqr.commerce.branch')` (wrong keys) — now uses `commerce_code` and `commerce_branch`
* **Bug**: `formatException` could fail when `getResponse()` is null (e.g. connection errors)
* **Bug**: `$_SERVER['HTTP_USER_AGENT']` undefined when running in CLI — now falls back to package identifier

### Deprecated
* Static `BancardQR::generate_qr()` and `BancardQR::revert()` — use Facade or inject `BancardQRManager`

## 1.0.1 - 2023-03-23

### Added
* Response object from the `revert` payment function

## 1.0.0 - 2023-03-23

### Added
* Improvement on the README 
* Fixes on properly parse errors response from the Bancard QR API.