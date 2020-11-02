# Add Custom View
A WordPress plugin that demonstrates how to add custom views to the _All Posts_ page using existing filters, hooks, functions, and techniques.

This is repository is part of [this post](https://tommcfarlin.com/add-a-custom-view/) which outlines how it works, why it works the way it does, and other considerations to keep in mind.

## How It Works

1. This is an `mu-plugin` so it belongs in the `mu-plugins` directory,
2. When you navigate to the _All Posts_ screen, a new link will appear next to _Published_, et. al.
3. If there are posts that are marked as _Uncategorized_, they will be accessible via this link; otherwise, the link is visible but as a count of `0` beside it.

## Installation
1. Download the main PHP file in this repository,
2. Drop it into your `mu-plugins` directory,
3. Begin using it.
