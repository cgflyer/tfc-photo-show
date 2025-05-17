# TFC Photo Show
Simple collect and display application for sharing photos during club events.
Uses an isolated folder to collect tagged photo submissions with metadata. As new images
are uploaded, they are scanned into a display list. The display list is randomly shuffled
and displayed in a random 3x3 grid of photos on the gallery page.

# Tech Stack
Uses php to configure a bootstrap display grid and javascript with Ajax to pull new photos
and arrange them in the display grid.

# upload process
A distinct upload target provides a form to input metadata like the tail number of the plane
and the location (airport) of the photo as well as any caption the author desires. The author
may add their name to the metadata file as well. The metadata fields are accepted as query
parameters so it is possible to generate a custom QR code with pre-filled field settings makes it easy for members to
post items with pre-filled metadata.

# TODO
* would be nice to include a settings page for changing the size of the grid and the timing and speed of the photo replacement.
* an export of the page would allow for a way to post the page as new content in the website CMS - this would take more effort
* secure the input with an event token generated at runtime and included in qr code
* validate the file input type


