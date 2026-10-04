# TaskFlow: TALL stack learning project

The owner is learning senior-level Laravel by building **TaskFlow**, a multi-tenant task manager (workspaces → projects → tasks → comments). It is a web app only: no mobile app and no public API in v1.

## How to work with the owner

- **Teaching mode A:** explain the concept, the owner writes the code, then review it like a pull request. They often ask for a worked example first, then write the rest themselves.
- **The owner runs every artisan, composer and git command.** Give them the exact command to run. Don't run commands that change the project. Read-only checks such as reading files, `git status`, `php -l` and `pint --test` are fine.
- They rate themselves 5/10. Explain the *why*, use small examples and tables, and keep steps short.
- "Show me" or "don't edit" means put the code in chat, split into small blocks with notes, for the owner to type. Edit files only when they explicitly ask ("fix it for me").
- They often ask you to "check" a file before running `composer lint`. Read it, run `php -l` and `pint --test`, compare it against the migration, and list issues by severity: what Pint can't fix first, then what `composer lint` will fix.
- They work on two machines (a PC and a laptop). Start each session from `git log` and `git status`, not from memory.
- **Study guide:** https://claude.ai/artifact/TaAKk2J9nwSVCAGH2ac62E. Every lesson and exercise is mirrored there. Read it to catch up, and add new lessons to it. If you can't edit it from this machine, publish new lessons as a separate page and tell the owner.

## Stack

Laravel 13, Livewire 4, Alpine, Tailwind, Pest 5, Larastan level 7, Pint. MySQL on Laravel Herd (browsed with Adminer). Tests run on in-memory SQLite (`phpunit.xml`), so migrations must work on both MySQL and SQLite. Laravel Boost was removed on purpose.

## Quality gates

- `composer test` runs `config:clear`, then Pint check, PHPStan (`--memory-limit=1G`), then Pest. CI runs the same checks on every push to `main`.
- `composer lint` fixes code style.
- PHPStan and Pest are slow on the laptop (PHPStan can take over 2 minutes, and `composer test` about 80 seconds). Run PHPStan checks in the background.
- `tests/Unit/ArchitectureTest.php` checks the `php` and `security` presets, bans `dd`/`dump`/`ray`, requires Actions to be `final` with a `handle()` method, and requires everything in `App\Enums` to be an enum.
- Commits are small and focused, using Conventional Commits (`feat:`, `fix:`, `test:`, `chore:`, `refactor:`, `docs:`).

## Architecture decisions

- Livewire components stay thin. Business logic lives in `app/Actions` (made with `make:class Actions/Name`, because there is no `make:action`).
- Create a folder only when its first file needs it.
- Model style follows `User.php`: `@property` docblocks, `#[Fillable([...])]`, a `casts()` method, and generic relation return types such as `@return BelongsTo<User, $this>`.
- Foreign keys that decide ownership (`workspace_id`, `project_id`, `creator_id`, `assignee_id`, `user_id`) are never fillable. Set them through relationships.
- Status, priority and role are `string(20)` columns backed by PHP string enums, not MySQL `ENUM`. Migrations use string literals, never app enums.
- Roles live on `memberships` (a pivot model with `#[Table(name: 'memberships', incrementing: true)]`), not on `users`.
- Every migration default the app reads is repeated in the model's `$attributes`, using string literals: Task `status => 'todo'`, `priority => 'medium'`; Membership `role => 'member'`.
- Add a relationship only when a feature needs it. Current ones: Workspace `members()`, `projects()`, `tasks()` (hasManyThrough); User `workspaces()`, `assignedTasks()`; Membership `workspace()`, `user()`; Project `workspace()`, `tasks()`; Task `project()`, `creator()`, `assignee()`, `comments()`; Comment `task()`, `author()` (column `user_id`).
- Indexes come from real queries: `[workspace_id, archived_at]`, `[project_id, status]`, `[assignee_id, status]`, and unique `[workspace_id, user_id]`.

## Gotchas found so far

- `make:enum Name` only puts the file in `app/Enums` if that folder already exists. Use the `Enums/` prefix only for the very first enum.
- Pivot models guess a singular table name and assume no auto-increment id.
- Pest's `php` preset bans `dump` and `ray` but not `dd`.
- A database default is not on the PHP object after `create()`. Laravel does not read the row back, so add defaults to `$attributes`.
- `belongsTo` guesses the column from the method name (`author()` → `author_id`). Pass the real column when the method is named by role.
- Relation generic order differs: `BelongsToMany<Target, $this, Pivot>` but `HasManyThrough<Target, Through, $this>`. `$this` goes only in the docblock, never in the arguments.
- `@return array <string, string>` (with a space) silently breaks the type for PHPStan, and Pint does not catch it.
- Factories run inside `Model::unguarded()`, so they may set ownership foreign keys. `#[Fillable]` only protects request input.
- Test a model default with `new Model()` or `$parent->children()->create([...])`, never with a factory: the factory sets the column itself and hides a missing `$attributes`.
- A "0 bad rows" query or a test without assertions passes on empty data. Count rows first, and add a "noise" row from another workspace to prove isolation.
- Seeders use `->recycle($team)` so creators, assignees and comment authors are real members of the workspace. `recycle` only replaces `Model::factory()` calls, so plain columns such as `assignee_id` need `->state()`.
- On this Windows machine `composer` runs through cmd.exe, which deletes `^` from the command line even inside quotes (`composer require "pkg:^1.40"` saves `"1.40"`). Edit the constraint in `composer.json` by hand, then run `composer update vendor/package`.
- Fortify: only `Features::registration()` is enabled, and the 2FA and passkeys migrations were deleted. Its generated classes in `App\Actions\Fortify` follow Fortify's contracts (`create()`, not final, plus a trait), so the arch test excludes them with `->ignoring('App\Actions\Fortify')`.
- Pest arch `->ignoring()` only applies to the expectation directly before it. In `->toBeFinal()->toHaveMethod('handle')->ignoring(...)`, `toBeFinal` still checks everything. Write one `arch()` per expectation, each with its own `ignoring()`.
- The editor's auto-import can pull in random `vendor/` classes (for example `PharIo\Manifest\Author`). Check imports in every review.

## Roadmap

1. Architecture and conventions (done)
2. Database design (done). Schema, enums, models, factories with states, relationship tests in `tests/Feature/Models`, and a demo seeder (`php artisan migrate:fresh --seed`, login `test@example.com` / `password`).
3. Auth and authorization: roles, policies, workspace scoping (next). The project started from the blank starter kit, so there is no login yet.
4. Core CRUD with Livewire
5. Alpine and Tailwind design system
6. Queues, events and notifications
7. Testing and quality
8. Performance, security and deployment

Check `git log --oneline` for the latest finished step.
