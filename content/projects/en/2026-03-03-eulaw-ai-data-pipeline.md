---
title: "eulaw.ai - Legal Data Pipeline"
date: 2026-03-03
slug: eulaw-ai-data-pipeline
excerpt: "Solo-built production data pipeline processing over a million EU and Danish legal documents across 15+ sources."
tech_stack: "Python, AWS, Serverless"
live_url: ""
github_url: ""
---

### The project

From November 2025 to February 2026, I worked as a solo data engineer for eulaw.ai. My job was to build a data pipeline that could ingest, process, and structure legal documents from across the EU and Denmark. The goal was to make a large and messy collection of legal texts searchable and usable.

When I started, there was an early version of the pipeline. It worked, but it had limitations that would make it hard to scale. I spent the first weeks understanding the existing system and the data landscape, then designed and built the pipeline from scratch.

### What I built

I developed adapters for 15+ different data sources, each with its own challenges: REST APIs, SPARQL endpoints, Atom feeds, sitemap-based crawls, and PDF extraction. Every source had a different way of organizing and serving its data, so each adapter needed its own approach to discovery, pagination, and error handling.

The system processes documents through a multi-layer architecture. Raw data comes in at the bottom, gets transformed and structured in the middle, and ends up as clean, delivery-ready data at the top. I owned the entire flow, from initial source analysis and evaluation to fully functional, tested, and deployed adapters.

By the end of the project, the pipeline had processed over a million documents. I made over 430 commits across the ~4 months, with a steady cadence throughout.

### The rebuild

A few months in, I realized the architecture I had built wasn't going to hold up well as the system grew. The way documents were identified and organized made it difficult to track updates and manage different sources consistently.

So I rebuilt it. Not a small refactor. A full redesign of how the system worked, while keeping everything running. It was a tough call to make, because it meant redoing work. But the new version was cleaner, more consistent, and much easier to extend with new sources.

Looking back, I think the decision to rebuild was one of the best I made on this project. It's easy to keep patching something that mostly works. It takes a different kind of confidence to admit it needs to be redone.

### Beyond the data work

I also contributed to infrastructure and CI/CD: setting up deployment pipelines, cloud configuration, and templates for the serverless architecture. I tried to take a pragmatic approach to operations, with a focus on monitoring, error handling, and cost optimization.

A lot of my work ended up being about edge cases and subtle data quality problems: character encoding issues, URL formatting, content type filtering. The kind of stuff that doesn't sound exciting but makes the difference between a pipeline that works in demos and one that works in production.

### What I learned

This was the project where I went from feeling like a junior developer to feeling like an actual data engineer. Not because someone gave me the title, but because the work demanded it.

I had to make architectural decisions on my own, manage complexity across multiple systems, and keep the whole thing reliable at scale. There was no team to fall back on. If something broke at 10 PM, it was my problem.

I learned a lot about building systems that need to handle messy, real-world data. Legal documents from different institutions don't follow the same standards. Formats vary, structures change, and edge cases are everywhere. Building something robust enough to handle that taught me more than any course ever did.

I also learned that I work well independently. I like having a team around me, but this project showed me I can own a complex system end-to-end and deliver something solid.

### Tech

Python on AWS with a serverless architecture. The pipeline handles data from REST APIs, SPARQL endpoints, Atom feeds, sitemap-based crawls, and PDFs. I also worked on deployment pipelines, cloud configuration, and monitoring.
