# GitHub Pages Setup Instructions

## Important Notes

### CNAME File

The `CNAME` file in this directory points to `docs.github.trarbach.dev`. 

**If you want to use the standard GitHub Pages URL** (`thibauttrarbach.github.io/daikinRCCloud`):
- Delete or rename the `CNAME` file
- GitHub Pages will use the default GitHub domain

**If you want to use a custom domain** (`docs.github.trarbach.dev`):
- Make sure the domain is properly configured in your DNS settings
- Add the domain in GitHub repository Settings → Pages → Custom domain
- The CNAME file should contain only the domain name

## Activating GitHub Pages

1. Go to your GitHub repository **Settings**
2. Navigate to **Pages** section
3. Under **Source**, select:
   - **Branch**: `main` (or `master` depending on your default branch)
   - **Folder**: `/docs`
4. Click **Save**

The documentation will be available at:
- Standard: `https://thibauttrarbach.github.io/daikinRCCloud/`
- Custom domain: `https://docs.github.trarbach.dev/` (if configured)

## Troubleshooting 404 Errors

If you get a 404 error:

1. **Check the branch**: Make sure you selected the correct branch (usually `main` or `master`)
2. **Check the folder**: Make sure `/docs` is selected
3. **Wait a few minutes**: GitHub Pages can take a few minutes to build after activation
4. **Check CNAME**: If using a custom domain, verify DNS configuration
5. **Check file structure**: Ensure `index.md` exists in the `docs/` folder
6. **Check Jekyll**: GitHub Pages uses Jekyll by default. The `_config.yml` file should be present

## File Structure

The documentation should have this structure:
```
docs/
├── _config.yml          # Jekyll configuration
├── index.md             # Homepage
├── fr_FR/
│   ├── index.md
│   ├── changelog.md
│   ├── index_beta.md
│   └── changelog_beta.md
└── [other languages]/
```

## Testing Locally

To test the documentation locally with Jekyll:

```bash
cd docs
bundle install
bundle exec jekyll serve
```

Then visit `http://localhost:4000` in your browser.

