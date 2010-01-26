<!-- EXTENSION ACCESS -->
<div class="tg">
	<h2><?php echo str_replace("{addon_name}", $this->name, $LANG->line("enable_extension_title")); ?></h2>
	<table>
		<tbody>
			<tr class="even">
				<th>
					<?php echo str_replace("{addon_name}",  $this->name, $LANG->line("enable_extension_label")); ?>
				</th>
				<td>
					<?php print $this->select_box(
						$settings["enabled"],
						array("1" => "yes", "0" => "no"),
						"Lg_htaccess_generator_ext[enabled]"
					); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<!-- EXTENSION ACCESS -->
<div class="tg">
	<h2><?php echo $LANG->line("htaccess_options_title"); ?></h2>
	<div class="info"><?php echo $LANG->line('htaccess_options_info'); ?></div>
	<table>
		<tbody>
			<tr class="even">
				<th scope="row">
					<?php echo $LANG->line('htaccess_path_label'); ?>
					<div class="note"><?php echo $LANG->line('htaccess_path_info'); ?></div>
				</th>
				<td>
					<input name="Lg_htaccess_generator_ext[htaccess_path]" type="text" value="<?php echo $REGX->form_prep($settings['htaccess_path']) ?>" />
					
				</td>
			</tr>
			<tr class="odd">
				<th scope="row">
					<?php echo $LANG->line('htaccess_template_label'); ?>
				</th>
				<td>
					<textarea name="Lg_htaccess_generator_ext[htaccess_template]" rows="20"><?php echo $REGX->form_prep($settings['htaccess_template']) ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<!-- CHECK FOR UPDATES -->
<div class="tg">
	<h2><?php echo $LANG->line("check_for_updates_title"); ?></h2>
	<div class="info"><?php echo str_replace("{addon_name}", $this->name, $LANG->line("check_for_updates_info")); ?></div>
	<table>
		<tbody>
			<tr class="even">
				<th><?php echo $LANG->line("check_for_updates_label") ?></th>
				<td>
					<select
						<?php if(!$lgau_enabled) : ?>
							disabled="disabled"
						<?php endif; ?>
						name="Lg_htaccess_generator_ext[check_for_updates]
					">
						<option value="1"<?php echo ($settings["check_for_updates"] == TRUE && $lgau_enabled === TRUE) ? 'selected="selected"' : ''; ?>>
							<?php echo $LANG->line("yes") ?>
						</option>
						<option value="0"<?php echo ($settings["check_for_updates"] == FALSE || $lgau_enabled === FALSE) ? 'selected="selected"' : ''; ?>>
							<?php echo $LANG->line("no") ?>
						</option>
					</select>
					<?php if(!$lgau_enabled) : ?>
						&nbsp;
						<span class='highlight'>LG Addon Updater is not installed and activated.</span>
						<input type="hidden" name="Lg_htaccess_generator_ext[check_for_updates]" value="0" />
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>