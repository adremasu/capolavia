require 'bootstrap-sass'

# Set this to the root of your project when deployed:

http_path = "/"
css_dir = "."
sass_dir = "inc/sass"
images_dir = "inc/images"
http_images_path = "inc/images"
javascripts_dir = "inc/js"
fonts_dir = "inc/fonts"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed

# DEVELOPMENT
output_style = :expanded

# PRODUCTION
# output_style = :compressed

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:

# DEVELOPMENT
# line_comments = true

# PRODUCTION
line_comments = false

# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass
preferred_syntax = :sass
