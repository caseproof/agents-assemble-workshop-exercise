# Agents Assemble: Spoken Script
### Building Teams That Work While You Sleep

---

## Slide 1 — Hook
*[1 min]*

"How many of you close your laptop and it keeps working?"

*(pause — let it land)*

"There's a version of this where you close your laptop and the work keeps going. That's what we're fixing today."

---

## Slide 2 — /loop: The On Switch
*[2 min]*

"This is /loop. Give it a task and a timer. It runs on repeat until you stop it. I use this to monitor deploys, watch for new issues, even run QA in the background while I work on something else. One command. Walk away."

"To stop it: tell Claude 'end the loop.' Two seconds. Or write the exit condition right into the prompt — 'stop when all tasks are done' — and it ends itself. You set the rules. It follows them."

---

## Slide 3 — /schedule: The Night Shift
*[1 min]*

"/schedule is different. This runs on Anthropic's cloud. Your machine can be off. It clones your repo, does the work, commits, and you wake up to the results."

"/loop is your desk assistant. /schedule is your night shift."

---

## Slide 4 — Custom Commands: Build Your Own Tools
*[2 min]*

"You can build your own slash commands. One markdown file, done. I have /standup that reads my git log and writes my standup. I have /brain that saves notes to my knowledge base. Each one is just a markdown file that tells Claude what to do."

*(show the file on screen — walk through the frontmatter and the instruction body)*

---

## Slide 5 — The Ralph Wiggum Loop
*[3 min]*

"I want to tell you about a Tuesday morning. I woke up, opened my laptop, and found 262 files committed. Tests passing. Everything working. I had gone to bed with a TODO list."

*(pause)*

"Here's how it actually works — and this is the part most people miss."

"Two files. That's the whole memory system. Every iteration, Ralph wakes up fresh — no memory of the previous run. But it reads these two files first. It knows what the requirements are, it knows what's already done, it picks the next failing requirement and builds it. Marks it pass. Exits. Loop starts again. This keeps going until every requirement in the PRD is marked pass."

"The technique is called eventual consistency. Ralph doesn't have to get it right the first time. He just has to keep trying. Each iteration is a fresh mind with a clear brief. That's not a bug — that's the design."

"Someone used this to build 6 repos at a Y Combinator hackathon. Another completed a $50K contract for $297 in API costs. The original technique was described by Jeffrey Huntley and later demonstrated by Gary Sims building a full MQTT server — from spec to working server — while the loop ran unattended."

"To stop it: /ralph-loop:cancel-ralph. One command. Always review before you ship. The tool is the labor. You are still the engineer."

---

## Slide 6 — Hooks: Automate the Boring Stuff
*[2 min]*

"I kept shipping without running tests. I'd push, realize it, feel stupid, revert. So I wrote a hook. Now every time I commit, Claude reminds me. I haven't made that mistake since. That's what hooks are for — catching the habits you keep forgetting you have."

"Hooks fire on events. After a commit? Remind me to test. After a file edit? Auto-format. Before a push? Run lint. You put this in `settings.json` and it just works. No plugins, no extensions. Ralph is the brain. Hooks are the guardrails."

---

## Slide 7 — CLAUDE.md + Personas: Brain and Identity
*[4 min]*

"Rakuten gave Claude a spec and walked away. 12.5 million lines of code. Seven hours. 99.9% accuracy. You know what they had that most teams skip? A CLAUDE.md that told the agent what it was, what it wasn't allowed to do, and what done looked like. Write the definition before you write the code. The agent doesn't need to be smart. It needs to know its job."

"But here's the piece almost nobody is doing yet — personas. You can give your agents identities. Not just instructions — *characters*. Watch what happens when the same PR goes to two different personas."

"Steve asks 'is this beautiful?' Margaret asks 'will this break at 3am?' You need both. A generic agent gives you one perspective. Personas give you a team with genuine tension — and tension is where the best work comes from."

"This is the piece most people skip. They build loops. They build pipelines. But they don't build *characters*. That's the difference between an automation and a team."

---

## Slide 8 — The Five Levels
*[2 min]*

"Most people see 'autonomous agent team' and assume they need a framework. Some orchestration layer. A PhD. You don't. You start at Level 1 tonight. You'll hit Level 3 by next week if you keep going. The tools are already there. The only thing between you and Level 5 is patience and a CLAUDE.md file."

"And the research backs the direction. Three focused agents consistently outperform one generalist working three times as long. Specialization multiplies. Give your agents a lane."

---

## Slide 9 — Great Minds: What Becomes Possible
*[3 min]*

"We used everything on that last slide to build a company. Loops. Schedules. Hooks. CLAUDE.md. Parallel agents. Role files. Personas. We gave Claude a product spec. Steve Jobs and Elon Musk debated the strategy. Margaret Hamilton ran QA — she wrote the code that landed on the moon, she can review yours. A board of directors voted on whether to ship. We went to sleep. That's what we woke up to."

"And you can build your own version right now. Open Claude Code and say: *'Build me a three-agent pipeline. Strategist, developer, QA. Parallel. Loop until QA passes.'* One sentence. Claude writes the CLAUDE.md files, the role definitions, the skill that orchestrates them. Your agents. Your rules. Your team."

---

## Slide 10 — Close
*[1 min]*

"You came in today knowing how to use Claude Code. You leave knowing how to make it work without you."

*(pause)*

"You are still the engineer. The agents work through the night. You are the one who ships it. That difference matters every single time."

*(pause)*

"Go build something tonight."

---

## Hands-On Introduction
*[transition into 15-minute session]*

"In the next 15 minutes, you're going to do something you won't forget. Open Claude Code."

> **Pro tip:** You don't have to create any of these files by hand. Just ask Claude: *"Create a TODO.md with three tasks"* and it will. Claude can create files, run bash commands, scaffold entire project structures. Ask it to do the setup for you.

---

## Resources

- **Ralph Wiggum Plugin:** `/plugin install ralph-loop@claude-plugins-official`
  https://awesomeclaude.ai/ralph-wiggum
- **Gary Sims — Ralph Demo (YouTube):**
  https://www.youtube.com/watch?v=A6vYr0dmQAY
- **Matt Pocock — Ralph Deep Dive:**
  https://www.youtube.com/watch?v=_IK18goX4X8
- **Great Minds Plugin:** `npx plugins add sethshouldes/great-minds-plugin`
- **Addy Osmani — The Code Agent Orchestra:**
  https://addyosmani.com/blog/code-agent-orchestra/
- **Addy Osmani — How to Write a Good Spec for AI Agents:**
  https://addyo.substack.com/p/how-to-write-a-good-spec-for-ai-agents
- **Anthropic — Agent Skills:**
  https://www.anthropic.com/engineering/equipping-agents-for-the-real-world-with-agent-skills
- **awesome-claude-code:**
  https://github.com/hesreallyhim/awesome-claude-code

---

## Timing Reference

| Section | Time |
|---|---|
| Slide 1 — Hook | 1 min |
| Slide 2 — /loop | 2 min |
| Slide 3 — /schedule | 1 min |
| Slide 4 — Custom Commands | 2 min |
| Slide 5 — Ralph Wiggum | 3 min |
| Slide 6 — Hooks | 2 min |
| Slide 7 — CLAUDE.md + Personas | 4 min |
| Slide 8 — Five Levels | 2 min |
| Slide 9 — Great Minds | 3 min |
| Slide 10 — Close | 1 min |
| Hands-on | 15 min |
| **Total** | **~36 min** |
