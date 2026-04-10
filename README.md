# Agents Assemble — Workshop Exercise

A PHP utility library with **intentional bugs**. Your job: let an autonomous AI agent find and fix them.

By the end of Exercise 4, you'll have an agent working through a real codebase — running tests, finding bugs, fixing them, running tests again — without being asked twice.
By the end of the Bonus, you'll have two agents running in parallel — one building, one monitoring.

That's not a demo. That's a workflow. Let's build it.

> **Presentation:** https://caseproof.github.io/agents-assemble-workshop-exercise/

---

## Setup (do this first)

```bash
git clone https://github.com/caseproof/agents-assemble-workshop-exercise.git
cd agents-assemble-workshop-exercise
composer install
```

Install the plugins:
```
/plugin install ralph-loop@claude-plugins-official
npx plugins add sethshoultes/great-minds-plugin
```

## Run Tests

```bash
composer test
# or
vendor/bin/phpunit
```

You should see **multiple failing tests**. That's the point.

---

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

---

## Exercises

> **Pro tip:** You don't have to create any of these files by hand. Claude Code can do it for you. Just say: *"Create a TODO.md with three tasks"* — and it will. Claude can create files, run bash commands, scaffold entire project structures. These exercises show the file contents so you know what's happening — but in practice, just ask Claude to make it.

---

### Exercise 1: /loop — Learn the On/Off Switch (2 min)

Before you build anything autonomous, learn how to start and stop it.

```
/loop 1m tell me a fun fact about the current time
```

Watch it run. Then tell Claude: `end the loop`

That's it. Now you know the switch. Everything else in this workshop depends on knowing you can stop it.

> **Exit conditions:** Instead of stopping manually, write the exit condition into the prompt: `"...when done, say ALL TASKS COMPLETE"` and pass `--completion-promise "ALL TASKS COMPLETE"`. The loop stops itself. You'll use this in every Ralph exercise.

---

### Exercise 2: Build a Custom Command (3 min)

One markdown file becomes one reusable slash command.

```bash
mkdir -p ~/.claude/commands
```

Create `~/.claude/commands/explain.md`:
```markdown
---
name: explain
description: Explain the current project
---

Read the README and package.json (or equivalent).
Give me a 3-sentence summary of what this project does.
```

Run it:
```
/explain
```

One markdown file = one command.

---

### Exercise 3: Give Your Agent a Brain (4 min)

First, ask Claude to review something *without* a CLAUDE.md:
```
Review the last commit.
```

Note the response. Now create a `CLAUDE.md` in this directory:
```markdown
# CLAUDE.md

You are Margaret Hamilton. You care about:
- Error handling and edge cases
- What happens when things go wrong
- Testing before shipping — always

When reviewing, ask: "What happens when this breaks at 3am?"
```

Ask again:
```
Review the last commit.
```

Twelve lines of markdown just changed how an AI reasons about your codebase. That's not configuration — that's personality.

---

### Exercise 4: Ralph Wiggum — The Persistent Builder (4 min)

Before applying Ralph to real code, learn the pattern with something simple.

Create a `TODO.md`:
```markdown
- [ ] Create a file called hello.txt that says "Hello from Ralph"
- [ ] Create a file called goodbye.txt that says "Goodbye from Ralph"
- [ ] Create a file called count.txt with the numbers 1 through 5, one per line
```

Run it:
```
/ralph-loop:ralph-loop "Read TODO.md. Pick one unchecked task. Do it. Mark it [x] in TODO.md. When all tasks are checked, say ALL TASKS COMPLETE."
  --completion-promise "ALL TASKS COMPLETE"
  --max-iterations 10
```

Watch it work through the list — one task, check it off, back for the next. When all three are done, it stops itself.

The TODO.md is the memory. Ralph reads it, sees what's checked, picks the next unchecked item, does it, marks it done, exits. The loop calls it again. The file is the state. This is the same principle behind every Ralph pattern.

To stop early: `/ralph-loop:cancel-ralph`

---

### Exercise 5: Ralph Wiggum — Fix Until It Passes (the main event)

Now apply that same pattern to a real codebase.

> **Ralph Wiggum Guide:** https://awesomeclaude.ai/ralph-wiggum

This repo has **intentional bugs** in `src/`. The tests in `tests/` are correct. Ralph's job: run the tests, find what's failing, fix the source, run again. Repeat until everything is green.

```
/ralph-loop:ralph-loop "Run vendor/bin/phpunit. If any tests fail, read the relevant file in src/, find the bug, fix it, and run the tests again. Only modify files in src/ — never modify tests/."
  --completion-promise "OK (36 tests"
  --max-iterations 15
```

Watch what happens. Ralph runs the tests, reads the failure output, opens the right source file, fixes the bug, runs again. Each module — StringUtils, ArrayUtils, ValidationUtils — gets fixed one failure at a time.

You gave it a broken codebase and a way to measure success. It did the rest.

> **What's actually happening:** Each Ralph iteration is a fresh agent — no memory of the previous run. The test output *is* the feedback loop. Ralph reads it, finds the failure, fixes it, runs again. The better your feedback loop, the better Ralph performs. PHPUnit's output tells Ralph exactly what broke and where. That's all it needs.

To stop early: `/ralph-loop:cancel-ralph`

---

### Exercise 6: Ralph With a Real PRD — The Full Pattern (take-home)

This is the original Ralph technique as described by Jeffrey Huntley. Instead of a TODO list, Ralph works from a structured requirements file — and writes its own memory between iterations.

> **Watch these first:**
> - https://www.youtube.com/watch?v=A6vYr0dmQAY — Gary Sims builds a complete MQTT server from spec to working code
> - https://www.youtube.com/watch?v=_IK18goX4X8 — Deep dive into prd.json + progress.txt, feedback loops, human-in-the-loop Ralph, and why small tasks matter

**The two-file memory system:**
- `prd.json` — what needs to be built, structured as user stories with `pass`/`fail` status
- `progress.txt` — notes the AI writes to itself about what it's done so far

**Step 1: Ask Claude to create the PRD**
```
Create a prd.json for a simple command-line calculator that supports add,
subtract, multiply, and divide. Format it as a JSON array of user stories,
each with: id, description, acceptance_criteria, and status (set to "fail").
```

**Step 2: Create an empty progress file**
```bash
echo "No progress yet. Starting fresh." > progress.txt
```

**Step 3: Run Ralph once — human in the loop**
```
/ralph-loop:ralph-loop "Read prd.json and progress.txt. Pick the highest priority user story where status is fail. Implement it. Write tests. Run them. If they pass, update prd.json to mark it pass. Append progress notes to progress.txt. Make a git commit. If all stories pass, say ALL STORIES PASSING."
  --completion-promise "ALL STORIES PASSING"
  --max-iterations 1
```

Check: Did it update `prd.json`? Did it write to `progress.txt`? Did it commit?

**Step 4: Let it run**
```
/ralph-loop:ralph-loop "Read prd.json and progress.txt. Pick the highest priority user story where status is fail. Implement it. Write tests. Run them. If they pass, mark it pass in prd.json. Append progress notes to progress.txt. Only work on ONE story per iteration. Make a git commit. If all stories pass, say ALL STORIES PASSING."
  --completion-promise "ALL STORIES PASSING"
  --max-iterations 20
```

Walk away. When you come back: every story marked `pass`, a git commit per feature, and `progress.txt` as a full log written by Ralph, for Ralph.

This is what the 262 files morning looked like. This is what the $50K contract looked like. A spec, two files, and a loop.

---

### Bonus: Two Agents, One Goal

1. Write a `CLAUDE.md` that defines your agent as a senior full-stack developer
2. Write a `TODO.md` with 5 tasks that together build a simple web page
3. Start Ralph working through the list:
   ```
   /ralph-loop:ralph-loop "Read TODO.md. Pick one unchecked task. Build it. Mark it [x] when done."
     --completion-promise "ALL TASKS COMPLETE"
     --max-iterations 10
   ```
4. While Ralph builds, open a second Claude Code window and run:
   ```
   /loop 2m check TODO.md and report how many tasks are complete vs remaining
   ```

Now you have two agents: one building, one monitoring. That's the beginning of a team.

---

### Try: Great Minds Debate (2 min)

```bash
npx plugins add sethshoultes/great-minds-plugin
/agency-debate "Should we build a mobile app or web app first?"
```

Watch Steve Jobs and Elon Musk argue about it. Then try it on a real decision you're facing.

---

## Going Deeper: `claude -p` (Headless Mode)

Everything in this workshop — `/loop`, Ralph Wiggum, `/schedule` — is built on top of `claude -p`. Runs in your regular terminal, no chat window, no interactive session.

```bash
claude -p "Run vendor/bin/phpunit. If any tests fail, fix the source file and run again." \
  --max-turns 10 \
  --max-budget-usd 0.50
```

| Flag | What it does |
|------|-------------|
| `-p "..."` | Run a prompt non-interactively |
| `--allowedTools "Read,Write,Edit,Bash"` | Control what Claude can touch |
| `--max-turns 10` | Cap how many tool calls Claude gets |
| `--max-budget-usd 1.00` | Spending cap |
| `--output-format json` | Structured output for pipelines |
| `--continue` | Resume the last conversation |

- **Inside Claude Code chat** → `/loop`, `/ralph-loop`, just talk to Claude
- **From your terminal, one-shot** → `claude -p`
- **In a cron job or CI/CD** → `claude -p`
- **Overnight, laptop closed** → `/schedule`

---

## Agent Teams

Once you've mastered loops and Ralph, the next level is coordinated multi-agent teams — multiple Claude instances working in parallel with defined roles, isolated worktrees, and structured handoffs.

```
"Build me a three-agent pipeline. Strategist, developer, QA.
Parallel. Loop until QA passes."
```

One sentence. Claude writes the CLAUDE.md files, the role definitions, and the orchestration. Your agents. Your rules. Your team.

**How it works:**
- Each agent gets a role definition (a markdown file describing its job, inputs, outputs, quality bar)
- Agents run in parallel using git worktrees for isolation — no concurrent writes, no conflicts
- The orchestrator dispatches work, collects output, routes failures back to the right agent
- The pipeline loops until a quality gate passes

**Read more:** https://code.claude.com/docs/en/agent-teams

---

## What's Next

- **`/schedule`** — cloud tasks that run without your laptop: `/schedule daily at 2am Read TODO.md and complete all unchecked tasks`
- **Great Minds Plugin** — full 14-persona agent team: `npx plugins add sethshoultes/great-minds-plugin` → `/agency-debate "your question"`
- **Build your own team** — *"Build me a three-agent pipeline. Strategist, developer, QA. Parallel. Loop until QA passes."* One sentence. Claude writes the whole thing.

---

## Resources

- **Ralph Wiggum Guide:** https://awesomeclaude.ai/ralph-wiggum
- **Gary Sims — Ralph Wiggum Demo:** https://www.youtube.com/watch?v=A6vYr0dmQAY
- **Matt Pocock — Ralph Deep Dive:** https://www.youtube.com/watch?v=_IK18goX4X8
- **Great Minds Plugin:** `npx plugins add sethshoultes/great-minds-plugin`
- **Addy Osmani — The Code Agent Orchestra:** https://addyosmani.com/blog/code-agent-orchestra/
- **Addy Osmani — How to Write a Good Spec for AI Agents:** https://addyo.substack.com/p/how-to-write-a-good-spec-for-ai-agents
- **Anthropic — Agent Skills:** https://www.anthropic.com/engineering/equipping-agents-for-the-real-world-with-agent-skills
- **awesome-claude-code:** https://github.com/hesreallyhim/awesome-claude-code
