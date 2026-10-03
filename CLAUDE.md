# TaskFlow: TALL stack learning project

The owner is learning senior-level Laravel by building **TaskFlow**, a multi-tenant task manager (workspaces → projects → tasks → comments). It is a web app only: no mobile app and no public API in v1.

## How to work with the owner

- **Teaching mode A:** explain the concept, the owner writes the code, then review it like a pull request. They often ask for a worked example first, then write the rest themselves.
- **The owner runs every artisan, composer and git command.** Give them the exact command to run. Don't run commands that change the project. Read-only checks such as reading files, `git status`, `php -l` and `pint --test` are fine.
- They rate themselves 5/10. Explain the *why*, use small examples and tables, and keep steps short.
- **Study guide:** https://claude.ai/artifact/TaAKk2J9nwSVCAGH2ac62E. Every lesson and exercise is mirrored there. Read it to catch up, and add new lessons to it. If you can't edit it from this machine, publish new lessons as a separate page and tell the owner.

## Stack

Laravel 13, Livewire 4, Alpine, Tailwind, Pest 5, Larastan level 7, Pint. MySQL on Laravel Herd (browsed with Adminer). Tests run on in-memory SQLite (`phpunit.xml`), so migrations must work on both MySQL and SQLite. Laravel Boost was removed on purpose.

## Quality gates

- `composer test` runs `config:clear`, then Pint check, PHPStan (`--memory-limit=1G`), then Pest. CI runs the same checks on every push to `main`.
- `composer lint` fixes code style.
- `tests/Unit/ArchitectureTest.php` checks the `php` and `security` presets, bans `dd`/`dump`/`ray`, requires Actions to be `final` with a `handle()` method, and requires everything in `App\Enums` to be an enum.
- Commits are small and focused, using Conventional Commits (`feat:`, `fix:`, `test:`, `chore:`, `refactor:`, `docs:`).

## Architecture decisions

- Livewire components stay thin. Business logic lives in `app/Actions` (made with `make:class Actions/Name`, because there is no `make:action`).
- Create a folder only when its first file needs it.
- Model style follows `User.php`: `@property` docblocks, `#[Fillable([...])]`, a `casts()` method, and generic relation return types such as `@return BelongsTo<User, $this>`.
- Foreign keys that decide ownership (`workspace_id`, `project_id`, `creator_id`, `assignee_id`, `user_id`) are never fillable. Set them through relationships.
- Status, priority and role are `string(20)` columns backed by PHP string enums, not MySQL `ENUM`. Migrations use string literals, never app enums.
- Roles live on `memberships` (a pivot model with `#[Table(name: 'memberships', incrementing: true)]`), not on `users`.
- Indexes come from real queries: `[workspace_id, archived_at]`, `[project_id, status]`, `[assignee_id, status]`, and unique `[workspace_id, user_id]`.

## Gotchas found so far

- `make:enum Name` only puts the file in `app/Enums` if that folder already exists. Use the `Enums/` prefix only for the very first enum.
- Pivot models guess a singular table name and assume no auto-increment id.
- Pest's `php` preset bans `dump` and `ray` but not `dd`.

## Roadmap

1. Architecture and conventions (done)
2. Database design. Part A, schema: done. Part B, enums and models: in progress. Part C: factories, seeders and relationship tests.
3. Auth and authorization: roles, policies, workspace scoping
4. Core CRUD with Livewire
5. Alpine and Tailwind design system
6. Queues, events and notifications
7. Testing and quality
8. Performance, security and deployment

Check `git log --oneline` for the latest finished step.
