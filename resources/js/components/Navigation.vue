<template>
	<v-navigation-drawer clipped fixed app v-model="navigation.show">
		<v-list>
			<v-list-tile v-for="(item, i) in navigation.items" :key="i" router :to="item.to" exact>
				<v-list-tile-action>
					<v-icon>{{ item.icon }}</v-icon>
				</v-list-tile-action>
				<v-list-tile-content>
					<v-list-tile-title v-text="item.title"></v-list-tile-title>
				</v-list-tile-content>
			</v-list-tile>
		</v-list>
	</v-navigation-drawer>
</template>

<script>
	export default {
		name: "Navigation",

		props: {
			navigation: Object
		}
	}
</script>

<style scoped>
.v-list__tile__avatar {
	border-radius: 3px;
	font-size: 13px;
	min-width: auto;
}
</style>