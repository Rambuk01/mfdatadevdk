---
title: From Science Teacher to Data Scientist
date: 2026-03-03
slug: from-science-teacher-to-data-scientist
excerpt: How I went from teaching math and physics at a Danish efterskole to building data pipelines and studying machine learning.
---

When the Danish government in the mid 2010s said that teachers were to learn and teach programming in the Danish elementary school, my first thought was NICE! However! I will NOT be teaching some scratch[y], block programming, where they get a frog or mouse to move from one side of the screen to the other.

I wanted to teach them something cool, some actual coding, something that could do something on the computer they sat on. Something that gave them insight into what went on under the hood of their own PCs.

So I started learning Java. I heard it was a good starting language, widely used, and I saw that Java powered the Unity Game Development platform (yea..). It would be cool, if my students could try their hands on actual professional tools.

I started learning, and *I loved it*. To this day, I am not sure what makes programming so much fun. Maybe it's the process of creating something, maybe it's the raw creative process, maybe it's the problemsolving, maybe it's the processes of building something line by line, part by part.. Almost like LEGO, but you're even building the LEGO blocks! I still don't know ;).

Either way. I was hooked. So I became a hobby programmer, and even got a job at an amazing *efterskole*, Nordborg Slots Efterskole. They had a focus on STEM and wanted a teacher to teach Math, Physics and programming. I think I wrote my application to them in 5 minutes. It was probably more of a loveletter..

Later, I created a small but time-consuming personal project in Python, automating a task that normally took each teacher around 1-2 hours of work pr. weekend: Assigning *efterskole* students to chores each weekend. It was pretty tedious.

I created a multi(!)-file Python script named **TJANS**, that did it all for us. I was so proud!

```
EVERY WEEKEND, a teacher had to:

    1. Open the sign-up spreadsheet from ViGGO
    2. Read through 100+ student names
    3. Figure out who's staying at school this weekend
    4. For each meal (breakfast, lunch, dinner):
        For each chore (cooking, dishes, tables & floors):
            Pick 3 students
            Write it down somewhere
    5. Manually create 15-20 calendar events in ViGGO
    6. Tag each student so they get notified

    Total time: 1-2 hours. Every. Weekend.


TJANS did this instead:

    1. Open the weekend spreadsheet
       For each student:
           Did they check "Jeg er på NSE"?  → add to weekend list
           Going home?                      → skip

    2. For each day in [Friday, Saturday, Sunday]:
         For each meal in [breakfast, lunch, dinner]:
           For each chore in [cooking, dishes, tables & floors]:

               Randomly pick(pop) students from the weekend list

               Create a calendar event in ViGGO:
                 Title:  "Dinner: Dishes"
                 Time:   17:45 - 18:45
                 Tagged: the picked students + the teachers on duty

    3. Print: "Students with no chore this weekend:"
       (Lucky them. But the list was good for backup)

    Total time: about 2 minutes.
```
Really short, right? Doesn't seem so complicated.
I quickly learned that even something this simple has a lot of moving parts. Here are some examples - It needed to:

- be able to read xlsx files.
- call the ViGGO API.
- get browser cookies, to create events.
- get internal IDs to tag students to specific calendar events.
- have logic to assign students.
- ask the teacher: "Who's on duty this weekend? (e.g. mf, jl)"
- etc. etc.

I guess that's another part of coding that I enjoy. Taking a larger problem, breaking it into smaller pieces, and solving them one at a time.

My automation script had no GUI. It was purely functional and text-based, and it was the first time I had created something with code, that actually saved time for me and others. Something that added *value*.

It was a great experience, and only increased my motivation to work in tech.

### The decision

I loved teaching. I want to be clear about that. I had great colleagues, great students, and I was at a school that let me teach the subjects I cared about. But over the years, the balance shifted. I spent my evenings and weekends programming, and my days teaching. At some point I had to be honest with myself: the thing I was most excited about wasn't my job.

So I started looking into what it would take to make the switch. And it turned out to be a lot.

I had a house in Sønderborg. I had my kids there. The closest university with a Data Science programme was SDU in Odense, 150 km away. There was no remote option. If I wanted to do this, I had to move.

That meant renting out my house, finding a place in Odense, and figuring out how to still be a present dad from a different city. My kids were young, and I wasn't willing to become a weekend-only parent. So I planned it so I'd have them every second week, from Thursday to Monday. That was non-negotiable.

The whole thing took over half a year to plan. It wasn't just about applying to university. It was about restructuring my entire life around a decision that might not work out.

But I believed it would. And in August 2023, I moved to Odense and started the MSc in Data Science at SDU.

### The grind

For the next two years, my weeks looked roughly like this: lectures and coursework Monday through Wednesday. Pick up my kids Thursday. Be a dad until Monday morning. Drive back. Repeat.

On top of that, I got a part-time job as a full-stack developer at Flexbuy, a small tech company in Odense. I needed the income and I loved getting the experience. University teaches you a lot, but working in a real codebase with real deadlines teaches you different things.

I studied hard. I'm not going to pretend it was easy or that I had some natural talent for it. I just put in the hours. I ended up with mostly A grades, which I'm proud of. Not because of the letters themselves, but because I know what they cost me in late nights, early mornings and weekends.

The hardest part wasn't the workload. It was the guilt of being far from my kids, combined with the pressure of knowing I had bet everything on this working out. There were weeks where I questioned the decision. But I kept going, because the alternative was going back to a career that no longer challenged me.

### What I built

During my studies, my master's thesis focused on time series forecasting with deep learning. I compared models like LSTM, TCN, and Transformer architectures on real-world data. My supervisor later said it was "an ambitious and well-executed project." That felt good.

After graduating, I got a freelance contract with eulaw.ai, where I built a data pipeline that processes 1.3 million EU and Danish legal documents. I designed the architecture myself, a medallion structure with bronze, silver, and knowledge base layers running on AWS. It was solo work from start to finish, and it was the first time I felt like a real data engineer.

Both of those projects exist because of the decision I made two years earlier. They wouldn't have happened if I'd stayed in the classroom.

### What it taught me

People sometimes ask me what the career change says about me. I think it says that I can plan, commit, and follow through on something difficult. That I can handle uncertainty and still deliver. And that I'm motivated by the work itself, not just the idea of it.

I also learned that my years as a teacher weren't wasted. I can explain a complex model to someone who's never seen one (sometimes, at least). I can stand in front of a room and make something confusing feel approachable, and I feel comfortable doing it. That turns out to be surprisingly useful in tech.

I loved teaching. I'm grateful for those years. But I love learning, problemsolving and building things even more.

If you're thinking about a similar change and want my opinion? Well, I sadly can't really help you. Each situation is unique.. I truly wish you the best though.
