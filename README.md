# WP-Workable

WP-Workable is a WordPress plugin that harnesses the Workable API to pull a list of all job listings associated with a particular company's account. It also has the ability to choose which listings are featured on a careers page. We (

## Installation

Clone the repo into the plugins directory.

```
cd /project-path/wp-content/plugins
git clone https://github.com/kanopi/wp-workable.git
```

## Activation

1. WP-CLI way with docksal

```bash
cd /project-path-root/
fin wp plugin activate wp-workable
```

2. Via WordPress Dashboard

- Go to Plugins -> Installed Plugins
- Find the plugin in the list, and click "Activate"

## Usage

To view the job listings on the back end, go to Settings -> Workable API. 

Here, you'll be able to determine which job listings are "live" on the website. They are highlighted in green and are bolded. To make it "live', use the checkbox in the far right column of a job listing row.

To reorder the active job listings, you can drag-and-drop the rows into whatever order you wish. The shortcodes associated with the active job listings will magically be ordered in the sequence you choose.

/// TODO: Figure out how to write in laymans terms the template side of things and not make it Kanopi specific

## Questions

Talk to Kat, or even Miriam. One of them can probably give you the answers you seek.





