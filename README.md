# Agents Assemble — Workshop Exercise

A PHP utility library with **intentional bugs**. Your job: let an autonomous AI agent fix them.

## Setup

```bash
git clone <repo-url>
cd agents-assemble-exercise
composer install
```

## Run Tests

```bash
composer test
# or
vendor/bin/phpunit
```

You should see **multiple failing tests**. That's the point.

## The Exercise

### Part 1: Autonomous Loop (RALPH)

Install the RALPH Wiggum plugin and let Claude Code fix all failing tests autonomously:

```bash
/plugin install ralph-wiggum@claude-plugins-official

/ralph-loop "Fix all failing tests in this repo. Run vendor/bin/phpunit after each fix. Only modify files in src/ — never modify tests. Output <promise>DONE</promise> when all tests pass." --max-iterations 10
```

Watch it work. Each iteration, Claude reads the test output, finds a failure, fixes the bug in `src/`, runs tests again.

### Part 2: Fork + Background Agent

Fork your session and give the fork a different task:

```bash
claude --fork-session
```

In the fork, try:
> "Add PHPDoc blocks to every public method in src/. Make sure existing tests still pass after your changes."

Push it to background with **Ctrl+B**, then check `/tasks` from your main session.

## What's Broken

Three modules, each with intentional bugs:

- **StringUtils** — `slugify()`, `truncate()`, `initials()`
- **ArrayUtils** — `flatten()`, `groupBy()`, `unique()`, `pluck()`
- **ValidationUtils** — `isValidEmail()`, `isStrongPassword()`, `isValidUrl()`

The tests are correct. The source code is not. Don't modify the tests.

## Rules

1. **Never modify files in `tests/`** — the tests define the correct behavior
2. **Only fix files in `src/`** — that's where the bugs are
3. **Run `vendor/bin/phpunit` to verify** — green tests = fixed bugs
