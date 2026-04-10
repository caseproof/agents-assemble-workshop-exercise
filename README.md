# Agents Assemble — Workshop Exercise

A PHP utility library with **intentional bugs**. Your job: let an autonomous AI agent find and fix them.

By the end of Exercise 4, you'll have an agent working through a real codebase — running tests, finding bugs, fixing them, running tests again — without being asked twice.
By the end of the Bonus, you'll have two agents running in parallel — one building, one monitoring.

That's not a demo. That's a workflow. Let's build it.

> **Presentation:** https://caseproof.github.io/agents-assemble-workshop-exercise/

---

## Table of Contents

- [Setup](#setup-do-this-first)
- [Run Tests](#run-tests)
- [What's Broken](#whats-broken)
- [Rules](#rules)
- [Exercises](#exercises)
  - [Exercise 1: /loop — On/Off Switch](#exercise-1-loop--learn-the-onoff-switch-2-min)
  - [Exercise 2: Build a Custom Command](#exercise-2-build-a-custom-command-3-min)
  - [Exercise 3: Give Your Agent a Brain](#exercise-3-give-your-agent-a-brain-4-min)
  - [Exercise 4: Ralph — The Persistent Builder](#exercise-4-ralph-wiggum--the-persistent-builder-4-min)
  - [Exercise 5: Ralph — Fix Until It Passes](#exercise-5-ralph-wiggum--fix-until-it-passes-the-main-event)
  - [Exercise 6: Ralph With a Real PRD](#exercise-6-ralph-with-a-real-prd--the-full-pattern-take-home)
  - [Bonus: Two Agents, One Goal](#bonus-two-agents-one-goal)
  - [Try: Great Minds Debate](#try-great-minds-debate-2-min)
- [Going Deeper: Custom Agents + Personas](#going-deeper-custom-agents--personas)
- [Going Deeper: Agent Teams](#going-deeper-agent-teams)
- [Going Deeper: Memory Systems](#going-deeper-memory-systems)
- [Going Deeper: `claude -p` Headless Mode](#going-deeper-claude--p-headless-mode)
- [What's Next](#whats-next)
- [Resources](#resources)

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

## Going Deeper: Custom Agents + Personas

You've already seen how CLAUDE.md gives an agent an identity. Sub-agents take that further — you define a named agent with a specific role, a system prompt, and a set of allowed tools. Then you call it by name.

```markdown
# team/qa-agent.md

You are Margaret Hamilton. You care about what breaks at 3am.
Your job: review the code in src/ and run the tests.
If anything fails, write a detailed bug report to reports/qa.md.
Do not fix anything — just report.
```

Then from your main Claude session:
```
Use the qa-agent to review the last commit.
```

**Why personas matter here:** A generic QA agent gives you generic feedback. Margaret Hamilton asks "what happens when this fails at 3am?" Steve Jobs asks "would I be proud to ship this?" The same review prompt, two completely different outputs — because identity shapes how an agent reasons, not just what it's told to do.

**Each sub-agent should have:**
- A clear role (what it does and doesn't do)
- A persona that shapes *how* it reasons
- Defined inputs (what to read) and outputs (what to produce)
- Explicit tool permissions (don't give a reviewer write access)

**The fastest way to create one:** just tell Claude Code what you need.

```
Create a sub-agent for code review. It should focus on security vulnerabilities
and performance issues. Assign it the Margaret Hamilton persona from agents/margaret-hamilton-qa.md.
```

Claude will generate the agent file, wire up the persona, and drop it in `.claude/agents/`. You can also build one from scratch with the `/agents` command, or write the markdown file directly — the format is just frontmatter + a system prompt.

**14 ready-made personas are in the [`agents/`](./agents) folder** — Margaret Hamilton, Steve Jobs, Elon Musk, Jony Ive, and 10 more. Clone the repo and they're ready to use as-is, or reference them as a starting point for your own agents:

```
Create a sub-agent for writing blog posts. Use agents/maya-angelou-writer.md as the persona.
```

**Read more:** https://code.claude.com/docs/en/sub-agents

---

## Going Deeper: Agent Teams

Once you've defined individual agents, the next level is coordinating them — multiple Claude instances working in parallel with structured handoffs.

```
"Build me a three-agent pipeline. Strategist, developer, QA.
Parallel. Loop until QA passes."
```

One sentence. Claude writes the role definitions and the orchestration. Your agents. Your rules. Your team.

**How it works:**
- Each agent runs in an isolated git worktree — no concurrent writes, no conflicts
- The orchestrator dispatches work, collects output, routes failures back to the right agent
- The pipeline loops until a quality gate passes

**Read more:** https://code.claude.com/docs/en/agent-teams

---

## Going Deeper: Memory Systems

By default, agent memory is file-based — `MEMORY.md` as an index, individual `.md` files for each memory, written and read by the agent across sessions. This works well for a single agent working on a single project.

When you have multiple agents, multiple projects, or need to search across accumulated knowledge, a database becomes the better tool.

**The pattern:** SQLite + embeddings. Each memory is stored as a row with a vector embedding alongside it. When an agent needs context, it searches by semantic similarity rather than exact file names.

```bash
# Add a learning after a project
memory add --type learning --agent "Margaret Hamilton" \
  --content "PHPUnit completion promise must match exact output string"

# Search before starting work
memory search "how did we handle PHPUnit output matching"
# → returns the most relevant memories by semantic similarity
```

**Why this matters for agent teams:** Individual agents have no shared memory by default. A database gives the whole team a common brain — Margaret's QA findings are visible to Steve's product review, Jensen's strategic decisions inform Elon's feasibility checks. The memory compounds across projects instead of resetting each session.

**The `memory:` field in agent frontmatter** connects an agent to Claude Code's built-in memory system — the same file-based approach, scoped per-agent. For most projects this is enough. When it isn't, a SQLite store is the natural next step.

**Implementation reference:** The Great Minds agency uses a SQLite + TF-IDF vector store with semantic search across 155+ accumulated memories — see [`sethshoultes/great-minds`](https://github.com/sethshoultes/great-minds) for the full implementation.

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
