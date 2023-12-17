This app packages the deprecated `html_2_pdf` function from the `files_scripts` Nextcloud app.

### html_to_pdf

`html_to_pdf(String html, [Table config]={}, [Table position]={}): string|nil`

Renders the HTML onto a PDF file.

A configuration table can be passed to configure various aspects of PDF generation. For more information see the [MPDF documentation](https://mpdf.github.io/reference/mpdf-variables/overview.html).  
The position (x, y, w, h) of where to render the HTML onto the page can also be provided. For more information see the [MPDF documentation](https://mpdf.github.io/reference/mpdf-functions/writefixedposhtml.html)

Returns the PDF as a string (or `nil` if PDF generation failed).
